<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use App\Models\EmployeeStatus;
use App\Services\EmployeeService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class EmployeeController extends BaseController
{
    public function __construct(
        protected EmployeeService $employeeService
    ) {
    }

    public function create()
    {
        try {
            $this->authorize('create', Employee::class);
            $companyId = $this->getCompanyId();
            $departments = Department::byCompany($companyId)->active()->get();
            $designations = Designation::byCompany($companyId)->active()->get();
            $statuses = EmployeeStatus::byCompany($companyId)->active()->get();
            return $this->view('employees.create', compact('departments', 'designations', 'statuses'));
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $companyId = $this->getCompanyId();
            $employee = Employee::byCompany($companyId)->with(['user', 'department', 'designation'])->findOrFail($id);
            $this->authorize('update', $employee);
            $departments = Department::byCompany($companyId)->active()->get();
            $designations = Designation::byCompany($companyId)->active()->get();
            $statuses = EmployeeStatus::byCompany($companyId)->active()->get();
            return $this->view('employees.create', compact('employee', 'departments', 'designations', 'statuses'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Employee not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function index()
    {
        try {
            $this->authorize('viewAny', Employee::class);
            $companyId = $this->getCompanyId();

            $departments = Department::byCompany($companyId)->active()->get();
            $designations = Designation::byCompany($companyId)->active()->get();
            $statuses = EmployeeStatus::byCompany($companyId)->active()->get();

            return $this->view('employees.index', compact('departments', 'designations', 'statuses'));
        } catch (\Exception $e) {
            return $this->error('Failed to load page.', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $this->authorize('viewAny', Employee::class);
            $companyId = $this->getCompanyId();

            $query = Employee::byCompany($companyId)
                ->with(['user', 'department', 'designation', 'reportingTo.user']);

            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('employee_code', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($uq) use ($search) {
                            $uq->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            }

            if ($request->filled('department_id')) {
                $query->where('department_id', $request->department_id);
            }

            if ($request->filled('designation_id')) {
                $query->where('designation_id', $request->designation_id);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('employment_type')) {
                $query->where('employment_type', $request->employment_type);
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

    public function store(EmployeeRequest $request)
    {
        try {
            $this->authorize('create', Employee::class);

            $employee = $this->employeeService->store([
                'company_id' => $this->getCompanyId(),
                'created_by' => auth()->id(),
                ...$request->validated(),
            ]);

            return $this->created('Employee created successfully.', $employee);
        } catch (\Exception $e) {
            return $this->error('Failed to create employee.', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $companyId = $this->getCompanyId();
            $employee = Employee::byCompany($companyId)
                ->with(['user', 'department', 'designation', 'reportingTo.user', 'salaries'])
                ->findOrFail($id);
            $this->authorize('view', $employee);

            return $this->view('employees.show', compact('employee'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Employee not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load employee details.', $e->getMessage());
        }
    }

    public function update(EmployeeRequest $request, $id)
    {
        try {
            $employee = Employee::findOrFail($id);
            $this->authorize('update', $employee);

            $this->employeeService->update($employee, $request->validated());

            return $this->updated('Employee updated successfully.', $employee->fresh()->load(['user', 'department', 'designation']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Employee not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to update employee.', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            $this->authorize('delete', $employee);

            if (auth()->user()->employee && auth()->user()->employee->id === (int) $id) {
                return $this->error('You cannot delete your own account.', null, 403);
            }

            $this->employeeService->destroy($employee);

            return $this->deleted('Employee deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Employee not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to delete employee.', $e->getMessage());
        }
    }

    public function import()
    {
        try {
            $this->authorize('create', Employee::class);
            return $this->view('employees.import');
        } catch (\Exception $e) {
            return $this->error('Failed to load import page.', $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        try {
            $this->authorize('viewAny', Employee::class);
            $companyId = $this->getCompanyId();

            $query = Employee::byCompany($companyId)
                ->with(['user', 'department', 'designation']);

            if ($request->filled('department_id')) {
                $query->where('department_id', $request->department_id);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $employees = $query->get();

            $filename = 'employees-' . now()->format('Y-m-d') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename={$filename}",
            ];

            $callback = function () use ($employees) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Employee Code', 'First Name', 'Last Name', 'Email', 'Department', 'Designation', 'Status', 'Employment Type', 'Joining Date']);

                foreach ($employees as $employee) {
                    fputcsv($file, [
                        $employee->employee_code,
                        $employee->user->first_name ?? '',
                        $employee->user->last_name ?? '',
                        $employee->user->email ?? '',
                        $employee->department->name ?? '',
                        $employee->designation->name ?? '',
                        $employee->status,
                        $employee->employment_type,
                        $employee->joining_date?->format('Y-m-d'),
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return $this->error('Failed to export employees.', $e->getMessage());
        }
    }
}
