<?php

namespace App\Http\Controllers;

use App\Http\Requests\PayrollRequest;
use App\Models\Employee;
use App\Models\Payroll;
use App\Services\PayrollService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PayrollController extends BaseController
{
    public function __construct(
        protected PayrollService $payrollService
    ) {
    }

    public function index()
    {
        try {
            $this->authorize('viewAny', Payroll::class);
            $years = range(date('Y') - 5, date('Y') + 1);
            return $this->view('payroll.index', compact('years'));
        } catch (\Exception $e) {
            return $this->error('Failed to load payroll page.', $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $this->authorize('create', Payroll::class);
            $companyId = $this->getCompanyId();
            $employees = Employee::byCompany($companyId)->with('user')->get();
            $months = range(1, 12);
            $years = range(date('Y') - 5, date('Y') + 1);
            return $this->view('payroll.create', compact('employees', 'months', 'years'));
        } catch (\Exception $e) {
            return $this->error('Failed to load payroll create form.', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $this->authorize('viewAny', Payroll::class);
            $companyId = $this->getCompanyId();

            $query = Payroll::byCompany($companyId)->with(['employee.user', 'creator']);

            if (auth()->user()->employee && !auth()->user()->hasRole(['Owner', 'Admin'])) {
                $query->where('employee_id', auth()->user()->employee->id);
            }

            if ($search = $request->input('search.value')) {
                $query->whereHas('employee.user', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            }

            if ($request->filled('month')) {
                $query->where('month', $request->month);
            }

            if ($request->filled('year')) {
                $query->where('year', $request->year);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('employee_id')) {
                $query->where('employee_id', $request->employee_id);
            }

            return $this->datatableResponse($query, $request);
        } catch (\Exception $e) {
            return response()->json([
                'draw' => (int) $request->input('draw', 0),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function generate(Request $request)
    {
        try {
            $this->authorize('create', Payroll::class);

            $request->validate([
                'month' => 'required|integer|between:1,12',
                'year' => 'required|integer|min:2020',
                'employee_ids' => 'nullable|array',
                'employee_ids.*' => 'exists:employees,id',
            ]);

            $companyId = $this->getCompanyId();
            $employeeIds = $request->employee_ids ?? [];
            $month = $request->month;
            $year = $request->year;
            $count = 0;
            $skipped = 0;

            $payrollPeriod = \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y');

            foreach ($employeeIds as $employeeId) {
                $exists = Payroll::byCompany($companyId)
                    ->where('employee_id', $employeeId)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                $this->payrollService->generatePayroll([
                    'company_id' => $companyId,
                    'employee_id' => $employeeId,
                    'created_by' => auth()->id(),
                    'month' => $month,
                    'year' => $year,
                    'payroll_period' => $payrollPeriod,
                    'status' => 'draft',
                ]);
                $count++;
            }

            $message = "Payroll generated for {$count} employee(s).";
            if ($skipped > 0) {
                $message .= " {$skipped} employee(s) skipped (already exist).";
            }

            return $this->created($message);
        } catch (ValidationException $e) {
            return $this->error('Validation failed.', $e->errors());
        } catch (\Exception $e) {
            return $this->error('Failed to generate payroll.', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $companyId = $this->getCompanyId();
            $payroll = Payroll::byCompany($companyId)
                ->with(['employee.user', 'creator'])
                ->findOrFail($id);
            $this->authorize('view', $payroll);

            return $this->view('payroll.show', compact('payroll'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Payroll record not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load payroll details.', $e->getMessage());
        }
    }

    public function update(PayrollRequest $request, $id)
    {
        try {
            $payroll = Payroll::findOrFail($id);
            $this->authorize('update', $payroll);

            $this->payrollService->update($payroll, $request->validated());

            return $this->updated('Payroll updated successfully.', $payroll->fresh()->load(['employee.user']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Payroll record not found.', null, 404);
        } catch (ValidationException $e) {
            return $this->error('Validation failed.', $e->errors());
        } catch (\Exception $e) {
            return $this->error('Failed to update payroll.', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $payroll = Payroll::findOrFail($id);
            $this->authorize('delete', $payroll);

            $this->payrollService->destroy($payroll);

            return $this->deleted('Payroll deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Payroll record not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to delete payroll.', $e->getMessage());
        }
    }

    public function bulkProcess(Request $request)
    {
        try {
            $this->authorize('create', Payroll::class);

            $request->validate([
                'payroll_ids' => 'required|array',
                'payroll_ids.*' => 'exists:payrolls,id',
                'status' => 'required|in:paid,cancelled',
            ]);

            $companyId = $this->getCompanyId();
            $count = 0;

            foreach ($request->payroll_ids as $payrollId) {
                $payroll = Payroll::byCompany($companyId)->find($payrollId);
                if ($payroll) {
                    $this->payrollService->update($payroll, [
                        'status' => $request->status,
                        'paid_at' => $request->status === 'paid' ? now() : null,
                    ]);
                    $count++;
                }
            }

            return $this->success("{$count} payroll(s) processed successfully.");
        } catch (ValidationException $e) {
            return $this->error('Validation failed.', $e->errors());
        } catch (\Exception $e) {
            return $this->error('Failed to process payrolls.', $e->getMessage());
        }
    }
}
