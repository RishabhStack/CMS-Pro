<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Holiday;
use App\Models\Announcement;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class DashboardController extends BaseController
{
    public function index()
    {
        try {
            $companyId = $this->getCompanyId();
            $isAdmin = auth()->user()->hasRole(['Owner', 'Admin']);
            $employee = auth()->user()->employee;
            $today = today();

            $upcomingHolidays = Holiday::byCompany($companyId)
                ->where('date', '>=', $today)
                ->where('status', 'active')
                ->orderBy('date')
                ->take(5)
                ->get();

            $announcements = Announcement::byCompany($companyId)
                ->where('status', 'published')
                ->where(function ($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })
                ->latest('published_at')
                ->take(5)
                ->get();

            if ($isAdmin) {
                $totalEmployees = Employee::byCompany($companyId)->active()->count();

                $todayAttendance = Attendance::byCompany($companyId)
                    ->where('date', $today)
                    ->selectRaw("COUNT(*) as total")
                    ->selectRaw("SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present")
                    ->selectRaw("SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent")
                    ->selectRaw("SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late")
                    ->selectRaw("SUM(CASE WHEN status = 'half-day' THEN 1 ELSE 0 END) as half_day")
                    ->first();

                $onLeave = Leave::byCompany($companyId)
                    ->whereDate('start_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today)
                    ->where('status', 'approved')
                    ->distinct('employee_id')
                    ->count('employee_id');

                $notMarked = $totalEmployees - ($todayAttendance->total ?? 0);

                $pendingLeaves = Leave::byCompany($companyId)
                    ->where('status', 'pending')
                    ->with(['employee.user', 'leaveType'])
                    ->latest()
                    ->take(8)
                    ->get();

                $weeklyTrend = collect();
                for ($i = 6; $i >= 0; $i--) {
                    $day = $today->copy()->subDays($i);
                    $dayLabel = $day->format('D');
                    $counts = Attendance::byCompany($companyId)
                        ->where('date', $day)
                        ->selectRaw("SUM(CASE WHEN status IN ('present','late') THEN 1 ELSE 0 END) as present")
                        ->selectRaw("SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent")
                        ->first();
                    $weeklyTrend->push([
                        'label' => $dayLabel,
                        'date' => $day->format('d/m'),
                        'present' => (int) ($counts->present ?? 0),
                        'absent' => (int) ($counts->absent ?? 0),
                    ]);
                }

                $departmentStats = Department::byCompany($companyId)
                    ->withCount(['employees' => function ($q) {
                        $q->active();
                    }])
                    ->get()
                    ->map(fn($d) => [
                        'name' => $d->name,
                        'count' => $d->employees_count,
                    ]);

                $maxDeptCount = $departmentStats->max('count') ?: 1;

                $newHires = Employee::byCompany($companyId)
                    ->whereMonth('joining_date', $today->month)
                    ->whereYear('joining_date', $today->year)
                    ->with('user')
                    ->take(6)
                    ->get();

                $anniversaries = Employee::byCompany($companyId)
                    ->active()
                    ->whereMonth('joining_date', $today->month)
                    ->whereDay('joining_date', '>=', $today->day)
                    ->with('user')
                    ->orderByRaw("DAY(joining_date)")
                    ->take(6)
                    ->get();

                $employmentTypes = Employee::byCompany($companyId)
                    ->active()
                    ->select('employment_type', DB::raw('count(*) as total'))
                    ->groupBy('employment_type')
                    ->get()
                    ->map(fn($e) => [
                        'type' => str_replace('_', ' ', ucfirst($e->employment_type ?? 'unknown')),
                        'count' => $e->total,
                    ]);

                return $this->view('dashboard.index', compact(
                    'totalEmployees', 'todayAttendance', 'onLeave', 'notMarked',
                    'pendingLeaves', 'weeklyTrend', 'departmentStats', 'maxDeptCount',
                    'newHires', 'anniversaries', 'employmentTypes',
                    'upcomingHolidays', 'announcements'
                ));
            }

            $todayStat = Attendance::byCompany($companyId)
                ->where('date', $today)
                ->selectRaw("COUNT(*) as total")
                ->selectRaw("SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present")
                ->selectRaw("SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent")
                ->selectRaw("SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late")
                ->selectRaw("SUM(CASE WHEN status = 'half-day' THEN 1 ELSE 0 END) as half_day")
                ->first();

            $todayOnLeave = Leave::byCompany($companyId)
                ->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->where('status', 'approved')
                ->distinct('employee_id')
                ->count('employee_id');

            $myAttendance = $employee ? Attendance::byCompany($companyId)
                ->where('employee_id', $employee->id)
                ->where('date', $today)
                ->first() : null;

            $myLeaves = $employee ? Leave::byCompany($companyId)
                ->where('employee_id', $employee->id)
                ->with('leaveType')
                ->latest()
                ->take(5)
                ->get() : collect();

            $myUpcomingLeaves = $employee ? Leave::byCompany($companyId)
                ->where('employee_id', $employee->id)
                ->where('status', 'approved')
                ->whereDate('start_date', '>=', $today)
                ->with('leaveType')
                ->orderBy('start_date')
                ->take(3)
                ->get() : collect();

            return $this->view('dashboard.employee', compact(
                'employee', 'myAttendance', 'myLeaves', 'myUpcomingLeaves',
                'upcomingHolidays', 'announcements', 'todayStat', 'todayOnLeave'
            ));
        } catch (\Exception $e) {
            return $this->error('Failed to load dashboard.', $e->getMessage());
        }
    }
}
