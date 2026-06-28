<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShiftSwapRequest as ShiftSwapRequestForm;
use App\Models\Employee;
use App\Models\ShiftAssignment;
use App\Models\ShiftSwapRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ShiftSwapRequestController extends BaseController
{
    public function index()
    {
        try {
            $this->authorize('viewAny', ShiftSwapRequest::class);
            $employees = Employee::byCompany($this->getCompanyId())->active()->get();
            return $this->view('shifts.swaps', compact('employees'));
        } catch (\Exception $e) {
            return $this->error('Failed to load page.', $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $this->authorize('create', ShiftSwapRequest::class);
            $companyId = $this->getCompanyId();
            $employees = Employee::byCompany($companyId)->active()->get();
            $user = auth()->user();
            $employee = Employee::where('user_id', $user->id)->first();

            $myAssignments = collect();
            if ($employee) {
                $myAssignments = ShiftAssignment::with('shift')
                    ->where('employee_id', $employee->id)
                    ->where('date', '>=', now()->toDateString())
                    ->get();
            }

            return $this->view('shifts.swap_form', compact('employees', 'myAssignments'));
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $this->authorize('viewAny', ShiftSwapRequest::class);
            $companyId = $this->getCompanyId();
            $user = auth()->user();

            $query = ShiftSwapRequest::with([
                'fromEmployee.user',
                'toEmployee.user',
                'shiftAssignment.shift',
            ])->where('company_id', $companyId);

            if (!$user->hasRole(['Owner', 'Admin'])) {
                $employee = Employee::where('user_id', $user->id)->first();
                if ($employee) {
                    $query->where(function ($q) use ($employee) {
                        $q->where('from_employee_id', $employee->id)
                            ->orWhere('to_employee_id', $employee->id);
                    });
                } else {
                    $query->whereRaw('1 = 0');
                }
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
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

    public function store(ShiftSwapRequestForm $request)
    {
        try {
            $this->authorize('create', ShiftSwapRequest::class);

            $user = auth()->user();
            $employee = Employee::where('user_id', $user->id)->first();

            if (!$employee) {
                return $this->error('No employee record found.');
            }

            $swapRequest = ShiftSwapRequest::create([
                'company_id' => $this->getCompanyId(),
                'from_employee_id' => $employee->id,
                ...$request->validated(),
            ]);

            return $this->created('Swap request submitted successfully.', $swapRequest->load([
                'fromEmployee.user', 'toEmployee.user', 'shiftAssignment.shift',
            ]));
        } catch (\Exception $e) {
            return $this->error('Failed to create swap request.', $e->getMessage());
        }
    }

    public function approve($id)
    {
        try {
            $swapRequest = ShiftSwapRequest::findOrFail($id);
            $this->authorize('update', $swapRequest);

            $swapRequest->update([
                'status' => 'approved',
                'responded_by' => auth()->id(),
                'responded_at' => now(),
            ]);

            $assignment = $swapRequest->shiftAssignment;
            if ($assignment) {
                $assignment->update(['employee_id' => $swapRequest->to_employee_id]);
            }

            return $this->success('Swap request approved successfully.', $swapRequest->fresh()->load([
                'fromEmployee.user', 'toEmployee.user', 'shiftAssignment.shift',
            ]));
        } catch (ModelNotFoundException $e) {
            return $this->error('Swap request not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to approve swap request.', $e->getMessage());
        }
    }

    public function reject($id)
    {
        try {
            $swapRequest = ShiftSwapRequest::findOrFail($id);
            $this->authorize('update', $swapRequest);

            $swapRequest->update([
                'status' => 'rejected',
                'responded_by' => auth()->id(),
                'responded_at' => now(),
            ]);

            return $this->success('Swap request rejected.', $swapRequest->fresh()->load([
                'fromEmployee.user', 'toEmployee.user', 'shiftAssignment.shift',
            ]));
        } catch (ModelNotFoundException $e) {
            return $this->error('Swap request not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to reject swap request.', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $swapRequest = ShiftSwapRequest::findOrFail($id);
            $this->authorize('delete', $swapRequest);

            $swapRequest->delete();

            return $this->deleted('Swap request deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Swap request not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to delete swap request.', $e->getMessage());
        }
    }
}
