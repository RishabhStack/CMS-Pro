<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShiftAssignmentRequest;
use App\Models\Employee;
use App\Models\Shift;
use App\Models\ShiftAssignment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ShiftAssignmentController extends BaseController
{
    public function index()
    {
        try {
            $this->authorize('viewAny', ShiftAssignment::class);
            $employees = Employee::byCompany($this->getCompanyId())->active()->get();
            $shifts = Shift::byCompany($this->getCompanyId())->active()->get();
            return $this->view('shifts.assignments', compact('employees', 'shifts'));
        } catch (\Exception $e) {
            return $this->error('Failed to load page.', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $this->authorize('viewAny', ShiftAssignment::class);
            $companyId = $this->getCompanyId();
            $user = auth()->user();

            $query = ShiftAssignment::with(['employee.user', 'shift'])
                ->whereHas('employee', function ($q) use ($companyId) {
                    $q->byCompany($companyId);
                });

            if (!$user->hasRole(['Owner', 'Admin'])) {
                $employee = Employee::where('user_id', $user->id)->first();
                if ($employee) {
                    $query->where('employee_id', $employee->id);
                } else {
                    $query->whereRaw('1 = 0');
                }
            }

            if ($request->filled('date')) {
                $query->where('date', $request->date);
            }

            if ($request->filled('employee_id')) {
                $query->where('employee_id', $request->employee_id);
            }

            if ($request->filled('shift_id')) {
                $query->where('shift_id', $request->shift_id);
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

    public function show($id)
    {
        try {
            $assignment = ShiftAssignment::with(['employee.user', 'shift'])->findOrFail($id);
            $this->authorize('view', $assignment);

            return $this->success('Assignment retrieved successfully.', $assignment);
        } catch (ModelNotFoundException $e) {
            return $this->error('Assignment not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve assignment.', $e->getMessage());
        }
    }

    public function store(ShiftAssignmentRequest $request)
    {
        try {
            $this->authorize('create', ShiftAssignment::class);

            $assignment = ShiftAssignment::updateOrCreate(
                ['employee_id' => $request->employee_id, 'date' => $request->date],
                ['shift_id' => $request->shift_id, 'notes' => $request->notes]
            );

            return $this->created('Shift assignment created successfully.', $assignment->load(['employee.user', 'shift']));
        } catch (\Exception $e) {
            return $this->error('Failed to create shift assignment.', $e->getMessage());
        }
    }

    public function update(ShiftAssignmentRequest $request, $id)
    {
        try {
            $assignment = ShiftAssignment::findOrFail($id);
            $this->authorize('update', $assignment);

            $assignment->update($request->validated());

            return $this->updated('Shift assignment updated successfully.', $assignment->fresh()->load(['employee.user', 'shift']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Shift assignment not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to update shift assignment.', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $assignment = ShiftAssignment::findOrFail($id);
            $this->authorize('delete', $assignment);

            $assignment->delete();

            return $this->deleted('Shift assignment deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Shift assignment not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to delete shift assignment.', $e->getMessage());
        }
    }

    public function bulkStore(Request $request)
    {
        try {
            $this->authorize('create', ShiftAssignment::class);

            $request->validate([
                'employee_ids' => 'required|array',
                'employee_ids.*' => 'exists:employees,id',
                'shift_id' => 'required|exists:shifts,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $startDate = \Carbon\Carbon::parse($request->start_date);
            $endDate = \Carbon\Carbon::parse($request->end_date);
            $dates = [];

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $dates[] = $date->format('Y-m-d');
            }

            $count = 0;
            foreach ($request->employee_ids as $employeeId) {
                foreach ($dates as $date) {
                    ShiftAssignment::updateOrCreate(
                        ['employee_id' => $employeeId, 'date' => $date],
                        ['shift_id' => $request->shift_id, 'notes' => $request->notes ?? null]
                    );
                    $count++;
                }
            }

            return $this->created("{$count} shift assignments created successfully.");
        } catch (\Exception $e) {
            return $this->error('Failed to create bulk assignments.', $e->getMessage());
        }
    }
}
