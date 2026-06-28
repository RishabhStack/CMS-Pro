<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Holiday;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends BaseController
{
    public function index()
    {
        return $this->view('calendar.index');
    }

    public function events(Request $request)
    {
        try {
            $companyId = $this->getCompanyId();
            $start = Carbon::parse($request->start);
            $end = Carbon::parse($request->end);
            $isAdmin = auth()->user()->hasRole(['Owner', 'Admin']);

            $events = [];

            $holidays = Holiday::byCompany($companyId)
                ->whereBetween('date', [$start, $end])
                ->where('status', 'active')
                ->get();

            foreach ($holidays as $holiday) {
                $events[] = [
                    'id' => 'holiday-' . $holiday->id,
                    'title' => $holiday->name,
                    'start' => $holiday->date->format('Y-m-d'),
                    'allDay' => true,
                    'backgroundColor' => '#f59e0b',
                    'borderColor' => '#f59e0b',
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'type' => 'holiday',
                        'description' => $holiday->type,
                    ],
                ];
            }

            $leavesQuery = Leave::byCompany($companyId)
                ->where('status', 'approved')
                ->where(function ($q) use ($start, $end) {
                    $q->whereBetween('start_date', [$start, $end])
                        ->orWhereBetween('end_date', [$start, $end])
                        ->orWhere(function ($q2) use ($start, $end) {
                            $q2->where('start_date', '<=', $start)
                                ->where('end_date', '>=', $end);
                        });
                })
                ->with(['employee.user', 'leaveType']);

            if (!$isAdmin && auth()->user()->employee) {
                $leavesQuery->where('employee_id', auth()->user()->employee->id);
            }

            $leaves = $leavesQuery->get();

            $leaveColors = [
                '#10b981', '#3b82f6', '#8b5cf6', '#ec4899',
                '#f97316', '#06b6d4', '#84cc16', '#6366f1',
            ];
            $colorIndex = 0;

            foreach ($leaves as $leave) {
                $color = $leaveColors[$colorIndex % count($leaveColors)];
                $colorIndex++;

                $events[] = [
                    'id' => 'leave-' . $leave->id,
                    'title' => $leave->employee->full_name . ' - ' . ($leave->leaveType->name ?? 'Leave'),
                    'start' => $leave->start_date->format('Y-m-d'),
                    'end' => $leave->end_date->copy()->addDay()->format('Y-m-d'),
                    'allDay' => true,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'type' => 'leave',
                        'employee_name' => $leave->employee->full_name,
                        'leave_type' => $leave->leaveType->name ?? 'Leave',
                        'total_days' => $leave->total_days,
                        'reason' => $leave->reason,
                    ],
                ];
            }

            if ($isAdmin) {
                $dates = [];
                $date = $start->copy();
                while ($date->lte($end)) {
                    $dates[] = $date->copy();
                    $date->addDay();
                }

                $totalEmployees = \App\Models\Employee::byCompany($companyId)->active()->count();

                foreach ($dates as $date) {
                    $dayAttendance = Attendance::byCompany($companyId)
                        ->where('date', $date)
                        ->selectRaw("COUNT(*) as total")
                        ->selectRaw("SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present")
                        ->selectRaw("SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent")
                        ->selectRaw("SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late")
                        ->selectRaw("SUM(CASE WHEN status = 'half-day' THEN 1 ELSE 0 END) as half_day")
                        ->first();

                    $total = (int) ($dayAttendance->total ?? 0);
                    if ($total === 0) continue;

                    $notMarked = $totalEmployees - $total;
                    $lines = [];
                    if ($dayAttendance->present > 0) $lines[] = "Present: {$dayAttendance->present}";
                    if ($dayAttendance->late > 0) $lines[] = "Late: {$dayAttendance->late}";
                    if ($dayAttendance->half_day > 0) $lines[] = "Half-day: {$dayAttendance->half_day}";
                    if ($dayAttendance->absent > 0) $lines[] = "Absent: {$dayAttendance->absent}";
                    if ($notMarked > 0) $lines[] = "Not Marked: {$notMarked}";

                    $events[] = [
                        'id' => 'attendance-' . $date->format('Ymd'),
                        'title' => implode(' | ', $lines),
                        'start' => $date->format('Y-m-d'),
                        'allDay' => true,
                        'display' => 'background',
                        'backgroundColor' => 'rgba(59, 130, 246, 0.08)',
                        'borderColor' => 'transparent',
                        'extendedProps' => [
                            'type' => 'attendance_summary',
                            'present' => (int) ($dayAttendance->present ?? 0),
                            'absent' => (int) ($dayAttendance->absent ?? 0),
                            'late' => (int) ($dayAttendance->late ?? 0),
                            'half_day' => (int) ($dayAttendance->half_day ?? 0),
                            'not_marked' => $notMarked,
                        ],
                    ];
                }
            }

            return response()->json($events);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
