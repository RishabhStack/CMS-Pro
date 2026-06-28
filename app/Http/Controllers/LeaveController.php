<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeaveRequest;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Services\LeaveService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LeaveController extends BaseController
{
    public function __construct(
        protected LeaveService $leaveService
    ) {
    }

    public function index()
    {
        try {
            $this->authorize('viewAny', Leave::class);
            $companyId = $this->getCompanyId();

            $leaveTypes = LeaveType::byCompany($companyId)->active()->get();

            $employee = auth()->user()->employee;

            $leaveBalances = $leaveTypes->map(function ($leaveType) use ($employee) {
                $used = Leave::where('employee_id', $employee?->id)
                    ->where('leave_type_id', $leaveType->id)
                    ->where('status', 'approved')
                    ->whereYear('start_date', now()->year)
                    ->sum('total_days');

                return (object) [
                    'name' => $leaveType->name,
                    'used' => (float) $used,
                    'total' => (int) $leaveType->days_per_year,
                    'color' => $leaveType->color,
                ];
            });

            return $this->view('leaves.index', compact('leaveTypes', 'leaveBalances'));
        } catch (\Exception $e) {
            return $this->error('Failed to load page.', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $this->authorize('viewAny', Leave::class);
            $companyId = $this->getCompanyId();

            $query = Leave::byCompany($companyId)
                ->with(['employee.user', 'leaveType', 'approvedBy', 'creator']);

            if (auth()->user()->employee && !auth()->user()->hasRole(['Owner', 'Admin'])) {
                $query->where('employee_id', auth()->user()->employee->id);
            }

            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('reason', 'like', "%{$search}%")
                        ->orWhereHas('employee.user', function ($uq) use ($search) {
                            $uq->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('leave_type_id')) {
                $query->where('leave_type_id', $request->leave_type_id);
            }

            if ($request->filled('employee_id')) {
                $query->where('employee_id', $request->employee_id);
            }

            if ($request->filled('date_from')) {
                $query->where('start_date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->where('end_date', '<=', $request->date_to);
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

    public function create()
    {
        try {
            $this->authorize('create', Leave::class);
            $leaveTypes = LeaveType::byCompany($this->getCompanyId())->active()->get();

            return $this->view('leaves.create', compact('leaveTypes'));
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function store(LeaveRequest $request)
    {
        try {
            $this->authorize('create', Leave::class);

            $employee = auth()->user()->employee;
            if (!$employee) {
                return $this->error('No employee record found.', null, 404);
            }

            $leave = $this->leaveService->applyLeave([
                'company_id' => $this->getCompanyId(),
                'created_by' => auth()->id(),
                'employee_id' => $employee->id,
                ...$request->validated(),
            ]);

            return $this->created('Leave application submitted successfully.', $leave->load(['employee.user', 'leaveType']));
        } catch (\Exception $e) {
            return $this->error('Failed to submit leave application.', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $companyId = $this->getCompanyId();
            $leave = Leave::byCompany($companyId)
                ->with(['employee.user', 'leaveType', 'approvedBy'])
                ->findOrFail($id);
            $this->authorize('view', $leave);

            return $this->view('leaves.show', compact('leave'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Leave record not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load leave details.', $e->getMessage());
        }
    }

    public function approve($id)
    {
        try {
            $leave = Leave::findOrFail($id);
            $this->authorize('approve', $leave);

            $leave = $this->leaveService->approveLeave($leave->id, auth()->id());

            return $this->success('Leave approved successfully.', $leave->load(['employee.user', 'leaveType', 'approvedBy']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Leave record not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to approve leave.', $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            $leave = Leave::findOrFail($id);
            $this->authorize('reject', $leave);

            $request->validate(['rejection_reason' => 'nullable|string|max:500']);

            $leave = $this->leaveService->rejectLeave(
                $leave->id,
                auth()->id(),
                $request->rejection_reason
            );

            return $this->success('Leave rejected successfully.', $leave->load(['employee.user', 'leaveType', 'approvedBy']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Leave record not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to reject leave.', $e->getMessage());
        }
    }

    public function cancel($id)
    {
        try {
            $leave = Leave::findOrFail($id);
            $this->authorize('cancel', $leave);

            $this->leaveService->update($leave, [
                'status' => 'cancelled',
            ]);

            return $this->success('Leave cancelled successfully.', $leave->fresh());
        } catch (ModelNotFoundException $e) {
            return $this->error('Leave record not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to cancel leave.', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $leave = Leave::findOrFail($id);
            $this->authorize('delete', $leave);

            $this->leaveService->destroy($leave);

            return $this->deleted('Leave record deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Leave record not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to delete leave record.', $e->getMessage());
        }
    }
}
