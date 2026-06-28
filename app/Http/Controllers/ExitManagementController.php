<?php

namespace App\Http\Controllers;

use App\Models\Resignation;
use App\Models\ClearanceChecklistItem;
use App\Models\ExitInterview;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExitManagementController extends BaseController
{
    public function index()
    {
        try {
            $this->authorize('viewAny', Resignation::class);

            $departments = ['IT', 'HR', 'Finance', 'Operations', 'Admin', 'Legal', 'Other'];

            return $this->view('exit-management.index', compact('departments'));
        } catch (\Exception $e) {
            return $this->error('Failed to load page.', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $this->authorize('viewAny', Resignation::class);
            $companyId = $this->getCompanyId();

            $query = Resignation::byCompany($companyId)
                ->with(['employee.user', 'approver', 'clearanceItems', 'exitInterview']);

            if (!auth()->user()->hasRole(['Owner', 'Admin'])) {
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

    public function store(Request $request)
    {
        try {
            $this->authorize('create', Resignation::class);

            $validated = $request->validate([
                'notice_date' => 'required|date',
                'last_working_date' => 'nullable|date|after_or_equal:notice_date',
                'reason' => 'nullable|string',
                'reason_category' => 'nullable|string|in:personal,career,health,relocation,other',
                'notice_period_days' => 'nullable|integer|min:0',
                'accrued_leave_payout' => 'nullable|numeric|min:0',
            ]);

            $resignation = Resignation::create([
                'company_id' => $this->getCompanyId(),
                'employee_id' => auth()->user()->employee->id,
                'created_by' => auth()->id(),
                'status' => 'pending',
                ...$validated,
            ]);

            return $this->created('Resignation submitted successfully.', $resignation->load(['employee.user']));
        } catch (\Exception $e) {
            return $this->error('Failed to submit resignation.', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $companyId = $this->getCompanyId();
            $resignation = Resignation::byCompany($companyId)
                ->with(['employee.user', 'approver', 'clearanceItems.assignee', 'clearanceItems.clearer', 'exitInterview.interviewer'])
                ->findOrFail($id);
            $this->authorize('view', $resignation);

            return $this->view('exit-management.show', compact('resignation'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Resignation not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load resignation.', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $companyId = $this->getCompanyId();
            $resignation = Resignation::byCompany($companyId)->findOrFail($id);
            $this->authorize('update', $resignation);

            $departments = ['IT', 'HR', 'Finance', 'Operations', 'Admin', 'Legal', 'Other'];

            return $this->view('exit-management.edit', compact('resignation', 'departments'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Resignation not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $resignation = Resignation::findOrFail($id);
            $this->authorize('update', $resignation);

            $validated = $request->validate([
                'notice_date' => 'required|date',
                'last_working_date' => 'nullable|date|after_or_equal:notice_date',
                'reason' => 'nullable|string',
                'reason_category' => 'nullable|string|in:personal,career,health,relocation,other',
                'notice_period_days' => 'nullable|integer|min:0',
                'accrued_leave_payout' => 'nullable|numeric|min:0',
            ]);

            $resignation->update($validated);

            return $this->updated('Resignation updated successfully.', $resignation->fresh()->load(['employee.user']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Resignation not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to update resignation.', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $resignation = Resignation::findOrFail($id);
            $this->authorize('delete', $resignation);

            $resignation->delete();

            return $this->deleted('Resignation deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Resignation not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to delete resignation.', $e->getMessage());
        }
    }

    public function approve($id)
    {
        try {
            $resignation = Resignation::findOrFail($id);
            $this->authorize('approve', $resignation);

            $resignation->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            return $this->success('Resignation approved successfully.', $resignation->fresh()->load(['employee.user', 'approver']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Resignation not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to approve resignation.', $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            $resignation = Resignation::findOrFail($id);
            $this->authorize('reject', $resignation);

            $request->validate(['rejection_reason' => 'nullable|string|max:1000']);

            $resignation->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'rejection_reason' => $request->rejection_reason,
            ]);

            return $this->success('Resignation rejected.', $resignation->fresh()->load(['employee.user', 'approver']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Resignation not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to reject resignation.', $e->getMessage());
        }
    }

    public function clearItem(Request $request, $id)
    {
        try {
            $item = ClearanceChecklistItem::findOrFail($id);
            $resignation = $item->resignation;
            $this->authorize('approve', $resignation);

            $item->update([
                'is_cleared' => $request->boolean('is_cleared', true),
                'cleared_by' => auth()->id(),
                'cleared_at' => $request->boolean('is_cleared', true) ? now() : null,
                'notes' => $request->notes,
            ]);

            return $this->success('Clearance item updated.', $item->fresh()->load(['clearer']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Clearance item not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to update clearance item.', $e->getMessage());
        }
    }

    public function saveInterview(Request $request, $id)
    {
        try {
            $resignation = Resignation::findOrFail($id);
            $this->authorize('approve', $resignation);

            $validated = $request->validate([
                'interview_date' => 'nullable|date',
                'interviewed_by' => 'nullable|exists:users,id',
                'overall_experience' => 'nullable|string',
                'reason_for_leaving' => 'nullable|string',
                'feedback_on_company' => 'nullable|string',
                'would_recommend' => 'nullable|boolean',
                'suggestions' => 'nullable|string',
            ]);

            $interview = ExitInterview::updateOrCreate(
                ['resignation_id' => $resignation->id],
                [
                    'created_by' => auth()->id(),
                    ...$validated,
                ]
            );

            return $this->success('Exit interview saved.', $interview->load(['interviewer']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Resignation not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to save exit interview.', $e->getMessage());
        }
    }
}
