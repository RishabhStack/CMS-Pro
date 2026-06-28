<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimesheetRequest;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Timesheet;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class TimesheetController extends BaseController
{
    public function index()
    {
        try {
            $this->authorize('viewAny', Timesheet::class);
            $projects = Project::byCompany($this->getCompanyId())->active()->get();
            return $this->view('timesheets.index', compact('projects'));
        } catch (\Exception $e) {
            return $this->error('Failed to load page.', $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $this->authorize('create', Timesheet::class);
            $projects = Project::byCompany($this->getCompanyId())->active()->get();
            return $this->view('timesheets.create', compact('projects'));
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $timesheet = Timesheet::findOrFail($id);
            $this->authorize('update', $timesheet);
            $projects = Project::byCompany($this->getCompanyId())->active()->get();
            return $this->view('timesheets.create', compact('timesheet', 'projects'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Timesheet not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $this->authorize('viewAny', Timesheet::class);
            $companyId = $this->getCompanyId();
            $user = auth()->user();

            $query = Timesheet::with(['employee.user', 'project', 'approver'])
                ->byCompany($companyId);

            if (!$user->hasRole(['Owner', 'Admin'])) {
                $employee = Employee::where('user_id', $user->id)->first();
                if ($employee) {
                    $query->where('employee_id', $employee->id);
                } else {
                    $query->whereRaw('1 = 0');
                }
            }

            if ($request->filled('date_from')) {
                $query->where('date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->where('date', '<=', $request->date_to);
            }

            if ($request->filled('project_id')) {
                $query->where('project_id', $request->project_id);
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

    public function store(TimesheetRequest $request)
    {
        try {
            $this->authorize('create', Timesheet::class);

            $user = auth()->user();
            $employee = Employee::where('user_id', $user->id)->first();
            if (!$employee && !$user->hasRole(['Owner', 'Admin'])) {
                return $this->error('No employee record found.');
            }

            $employeeId = $employee ? $employee->id : $request->employee_id;

            $timesheet = Timesheet::create([
                'company_id' => $this->getCompanyId(),
                'employee_id' => $employeeId,
                ...$request->validated(),
                'status' => 'draft',
            ]);

            return $this->created('Timesheet created successfully.', $timesheet->load(['employee.user', 'project']));
        } catch (\Exception $e) {
            return $this->error('Failed to create timesheet.', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $companyId = $this->getCompanyId();
            $timesheet = Timesheet::byCompany($companyId)->with(['employee.user', 'project', 'approver'])->findOrFail($id);
            $this->authorize('view', $timesheet);

            return $this->success('Timesheet retrieved successfully.', $timesheet);
        } catch (ModelNotFoundException $e) {
            return $this->error('Timesheet not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve timesheet.', $e->getMessage());
        }
    }

    public function update(TimesheetRequest $request, $id)
    {
        try {
            $timesheet = Timesheet::findOrFail($id);
            $this->authorize('update', $timesheet);

            $data = $request->validated();
            if ($timesheet->status !== 'draft') {
                unset($data['total_hours']);
            }

            $timesheet->update($data);

            return $this->updated('Timesheet updated successfully.', $timesheet->fresh()->load(['employee.user', 'project']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Timesheet not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to update timesheet.', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $timesheet = Timesheet::findOrFail($id);
            $this->authorize('delete', $timesheet);

            $timesheet->delete();

            return $this->deleted('Timesheet deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Timesheet not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to delete timesheet.', $e->getMessage());
        }
    }

    public function approve($id)
    {
        try {
            $timesheet = Timesheet::findOrFail($id);
            $this->authorize('approve', $timesheet);

            $timesheet->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            return $this->success('Timesheet approved successfully.', $timesheet->fresh()->load(['employee.user', 'project', 'approver']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Timesheet not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to approve timesheet.', $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            $timesheet = Timesheet::findOrFail($id);
            $this->authorize('approve', $timesheet);

            $request->validate(['rejection_reason' => 'required|max:1000']);

            $timesheet->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'rejection_reason' => $request->rejection_reason,
            ]);

            return $this->success('Timesheet rejected.', $timesheet->fresh()->load(['employee.user', 'project', 'approver']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Timesheet not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to reject timesheet.', $e->getMessage());
        }
    }
}
