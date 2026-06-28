<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Payroll;
use App\Models\SavedReport;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends BaseController
{
    public function index()
    {
        return $this->view('reports.index');
    }

    public function attendanceTrend()
    {
        $companyId = $this->getCompanyId();
        $months = collect();
        $labels = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M Y');
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            $stats = Attendance::byCompany($companyId)
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->selectRaw("
                    SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present,
                    SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent,
                    SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late
                ")
                ->first();

            $months->push([
                'present' => (int) ($stats->present ?? 0),
                'absent' => (int) ($stats->absent ?? 0),
                'late' => (int) ($stats->late ?? 0),
            ]);
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Present',
                    'data' => $months->pluck('present'),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16,185,129,0.1)',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Absent',
                    'data' => $months->pluck('absent'),
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239,68,68,0.1)',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Late',
                    'data' => $months->pluck('late'),
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245,158,11,0.1)',
                    'tension' => 0.3,
                ],
            ],
        ]);
    }

    public function leaveTrend()
    {
        $companyId = $this->getCompanyId();
        $leaveTypes = LeaveType::byCompany($companyId)->pluck('name', 'id');
        $labels = [];

        for ($i = 11; $i >= 0; $i--) {
            $labels[] = now()->subMonths($i)->format('M Y');
        }

        $datasets = [];
        $colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6', '#f97316'];

        foreach ($leaveTypes as $id => $name) {
            $data = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $count = Leave::byCompany($companyId)
                    ->where('leave_type_id', $id)
                    ->where('status', 'approved')
                    ->whereYear('start_date', $date->year)
                    ->whereMonth('start_date', $date->month)
                    ->sum('total_days');

                $data[] = (float) $count;
            }

            $datasets[] = [
                'label' => $name,
                'data' => $data,
                'backgroundColor' => $colors[array_search($id, array_keys($leaveTypes->toArray())) % count($colors)] ?? '#6b7280',
            ];
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => $datasets,
        ]);
    }

    public function payrollSummary()
    {
        $companyId = $this->getCompanyId();
        $year = now()->year;
        $labels = [];

        for ($m = 1; $m <= 12; $m++) {
            $labels[] = \Carbon\Carbon::createFromDate($year, $m, 1)->format('M');
        }

        $earnings = [];
        $deductions = [];
        $net = [];

        for ($m = 1; $m <= 12; $m++) {
            $totals = Payroll::byCompany($companyId)
                ->where('year', $year)
                ->where('month', $m)
                ->where('status', 'paid')
                ->selectRaw("
                    COALESCE(SUM(total_earnings), 0) as total_earnings,
                    COALESCE(SUM(total_deductions), 0) as total_deductions,
                    COALESCE(SUM(net_salary), 0) as net_salary
                ")
                ->first();

            $earnings[] = (float) ($totals->total_earnings ?? 0);
            $deductions[] = (float) ($totals->total_deductions ?? 0);
            $net[] = (float) ($totals->net_salary ?? 0);
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Earnings',
                    'data' => $earnings,
                    'backgroundColor' => '#10b981',
                ],
                [
                    'label' => 'Deductions',
                    'data' => $deductions,
                    'backgroundColor' => '#ef4444',
                ],
                [
                    'label' => 'Net Pay',
                    'data' => $net,
                    'backgroundColor' => '#3b82f6',
                ],
            ],
        ]);
    }

    public function headcount()
    {
        $companyId = $this->getCompanyId();

        $departmentData = Department::byCompany($companyId)
            ->withCount(['employees' => function ($q) {
                $q->active();
            }])
            ->get();

        $employmentTypes = Employee::byCompany($companyId)
            ->active()
            ->select('employment_type', DB::raw('count(*) as total'))
            ->groupBy('employment_type')
            ->get();

        return response()->json([
            'departments' => [
                'labels' => $departmentData->pluck('name'),
                'datasets' => [
                    [
                        'label' => 'Headcount',
                        'data' => $departmentData->pluck('employees_count'),
                        'backgroundColor' => ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6', '#f97316'],
                    ],
                ],
            ],
            'employmentTypes' => [
                'labels' => $employmentTypes->map(fn($e) => str_replace('_', ' ', ucfirst($e->employment_type ?? 'unknown'))),
                'datasets' => [
                    [
                        'data' => $employmentTypes->pluck('total'),
                        'backgroundColor' => ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                    ],
                ],
            ],
        ]);
    }

    public function turnoverRate()
    {
        $companyId = $this->getCompanyId();
        $labels = [];

        for ($i = 11; $i >= 0; $i--) {
            $labels[] = now()->subMonths($i)->format('M Y');
        }

        $hires = [];
        $terminations = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            $hired = Employee::byCompany($companyId)
                ->whereBetween('joining_date', [$monthStart, $monthEnd])
                ->count();

            $terminated = Employee::byCompany($companyId)
                ->whereBetween('exit_date', [$monthStart, $monthEnd])
                ->where('status', 'inactive')
                ->count();

            $hires[] = $hired;
            $terminations[] = $terminated;
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Hires',
                    'data' => $hires,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16,185,129,0.1)',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Terminations',
                    'data' => $terminations,
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239,68,68,0.1)',
                    'tension' => 0.3,
                ],
            ],
        ]);
    }

    public function saveReport(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:attendance,leave,payroll,headcount,turnover',
            'filters' => 'nullable|array',
        ]);

        $report = SavedReport::create([
            'company_id' => $this->getCompanyId(),
            'name' => $request->name,
            'type' => $request->type,
            'filters' => $request->filters,
            'created_by' => auth()->id(),
        ]);

        return $this->created('Report saved successfully', $report);
    }

    public function savedReports()
    {
        $reports = SavedReport::byCompany($this->getCompanyId())
            ->with('creator')
            ->latest()
            ->get()
            ->map(fn($r) => [
                'id' => $r->id,
                'name' => $r->name,
                'type' => $r->type,
                'filters' => $r->filters,
                'created_by' => $r->creator?->first_name . ' ' . $r->creator?->last_name,
                'created_at' => $r->created_at->format('d M Y'),
            ]);

        return response()->json($reports);
    }
}
