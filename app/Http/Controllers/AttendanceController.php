<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceRequest;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Services\AttendanceService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AttendanceController extends BaseController
{
    public function __construct(
        protected AttendanceService $attendanceService
    ) {
    }

    public function index()
    {
        try {
            $this->authorize('viewAny', Attendance::class);
            $companyId = $this->getCompanyId();
            $today = now()->format('Y-m-d');
            $departments = Department::byCompany($companyId)->active()->get();
            return $this->view('attendance.index', compact('today', 'departments'));
        } catch (\Exception $e) {
            return $this->error('Failed to load page.', $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $this->authorize('create', Attendance::class);
            $companyId = $this->getCompanyId();
            $employees = Employee::byCompany($companyId)->active()->get();
            return $this->view('attendance.create', compact('employees'));
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $attendance = Attendance::findOrFail($id);
            $this->authorize('update', $attendance);
            $companyId = $this->getCompanyId();
            $employees = Employee::byCompany($companyId)->active()->get();
            return $this->view('attendance.create', compact('attendance', 'employees'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Attendance record not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $this->authorize('viewAny', Attendance::class);
            $companyId = $this->getCompanyId();

            $query = Attendance::byCompany($companyId)->with(['employee.user', 'creator']);

            if (auth()->user()->employee && !auth()->user()->hasRole(['Owner', 'Admin'])) {
                $query->where('employee_id', auth()->user()->employee->id);
            }

            if ($search = $request->input('search.value')) {
                $query->whereHas('employee.user', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            }

            if ($request->filled('date_from')) {
                $query->where('date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->where('date', '<=', $request->date_to);
            }

            if ($request->filled('date')) {
                $query->where('date', $request->date);
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

    public function clockIn()
    {
        try {
            $this->authorize('create', Attendance::class);

            $user = auth()->user();
            $employee = $user->employee;

            if (!$employee) {
                return $this->error('No employee record found.', null, 404);
            }

            $existingToday = $this->attendanceService->getTodayAttendance($employee->id, today()->format('Y-m-d'));
            if ($existingToday && $existingToday->clock_in) {
                return $this->error('Already clocked in today.', null, 422);
            }

            $attendance = $this->attendanceService->clockIn(
                $employee->id,
                today()->format('Y-m-d'),
                now()->format('H:i:s')
            );

            return $this->success('Clocked in successfully.', $attendance);
        } catch (\Exception $e) {
            return $this->error('Failed to clock in.', $e->getMessage());
        }
    }

    public function clockOut()
    {
        try {
            $this->authorize('create', Attendance::class);

            $user = auth()->user();
            $employee = $user->employee;

            if (!$employee) {
                return $this->error('No employee record found.', null, 404);
            }

            $todayAttendance = $this->attendanceService->getTodayAttendance($employee->id, today()->format('Y-m-d'));
            if (!$todayAttendance || !$todayAttendance->clock_in) {
                return $this->error('Not clocked in today.', null, 422);
            }

            if ($todayAttendance->clock_out) {
                return $this->error('Already clocked out today.', null, 422);
            }

            $attendance = $this->attendanceService->clockOut(
                $employee->id,
                today()->format('Y-m-d'),
                now()->format('H:i:s')
            );

            return $this->success('Clocked out successfully.', $attendance);
        } catch (\Exception $e) {
            return $this->error('Failed to clock out.', $e->getMessage());
        }
    }

    public function markBreak()
    {
        try {
            $this->authorize('create', Attendance::class);

            $user = auth()->user();
            $employee = $user->employee;

            if (!$employee) {
                return $this->error('No employee record found.', null, 404);
            }

            $todayAttendance = $this->attendanceService->getTodayAttendance($employee->id, today()->format('Y-m-d'));
            if (!$todayAttendance || !$todayAttendance->clock_in) {
                return $this->error('Not clocked in today.', null, 422);
            }

            if ($todayAttendance->break_start && !$todayAttendance->break_end) {
                return $this->error('Break already started.', null, 422);
            }

            $attendance = $this->attendanceService->markBreak(
                $employee->id,
                today()->format('Y-m-d'),
                now()->format('H:i:s')
            );

            return $this->success('Break started successfully.', $attendance);
        } catch (\Exception $e) {
            return $this->error('Failed to start break.', $e->getMessage());
        }
    }

    public function endBreak()
    {
        try {
            $this->authorize('create', Attendance::class);

            $user = auth()->user();
            $employee = $user->employee;

            if (!$employee) {
                return $this->error('No employee record found.', null, 404);
            }

            $todayAttendance = $this->attendanceService->getTodayAttendance($employee->id, today()->format('Y-m-d'));
            if (!$todayAttendance || !$todayAttendance->break_start) {
                return $this->error('No active break found.', null, 422);
            }

            if ($todayAttendance->break_end) {
                return $this->error('Break already ended.', null, 422);
            }

            $attendance = $this->attendanceService->endBreak(
                $employee->id,
                today()->format('Y-m-d'),
                now()->format('H:i:s')
            );

            return $this->success('Break ended successfully.', $attendance);
        } catch (\Exception $e) {
            return $this->error('Failed to end break.', $e->getMessage());
        }
    }

    public function store(AttendanceRequest $request)
    {
        try {
            $this->authorize('create', Attendance::class);

            $attendance = $this->attendanceService->store([
                'company_id' => $this->getCompanyId(),
                'created_by' => auth()->id(),
                ...$request->validated(),
            ]);

            return $this->created('Attendance record created successfully.', $attendance);
        } catch (\Exception $e) {
            return $this->error('Failed to create attendance record.', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $companyId = $this->getCompanyId();
            $attendance = Attendance::byCompany($companyId)
                ->with(['employee.user', 'creator'])
                ->findOrFail($id);
            $this->authorize('view', $attendance);

            return $this->success('Attendance record retrieved successfully.', $attendance);
        } catch (ModelNotFoundException $e) {
            return $this->error('Attendance record not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve attendance record.', $e->getMessage());
        }
    }

    public function update(AttendanceRequest $request, $id)
    {
        try {
            $attendance = Attendance::findOrFail($id);
            $this->authorize('update', $attendance);

            $this->attendanceService->update($attendance, $request->validated());

            return $this->updated('Attendance record updated successfully.', $attendance->fresh()->load(['employee.user']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Attendance record not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to update attendance record.', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $attendance = Attendance::findOrFail($id);
            $this->authorize('delete', $attendance);

            $this->attendanceService->destroy($attendance);

            return $this->deleted('Attendance record deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Attendance record not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to delete attendance record.', $e->getMessage());
        }
    }
}
