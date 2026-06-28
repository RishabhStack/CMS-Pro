@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stats-card border-start border-primary border-4">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stats-icon rounded-circle bg-primary bg-opacity-10 p-2">
                        <i class="bi bi-people fs-4 text-primary"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0 small text-uppercase fw-semibold">Total</h6>
                        <h3 class="mb-0 fw-bold">{{ $totalEmployees }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stats-card border-start border-success border-4">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stats-icon rounded-circle bg-success bg-opacity-10 p-2">
                        <i class="bi bi-check-circle fs-4 text-success"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0 small text-uppercase fw-semibold">Present</h6>
                        <h3 class="mb-0 fw-bold">{{ $todayAttendance->present ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stats-card border-start border-danger border-4">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stats-icon rounded-circle bg-danger bg-opacity-10 p-2">
                        <i class="bi bi-x-circle fs-4 text-danger"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0 small text-uppercase fw-semibold">Absent</h6>
                        <h3 class="mb-0 fw-bold">{{ $todayAttendance->absent ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stats-card border-start border-warning border-4">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stats-icon rounded-circle bg-warning bg-opacity-10 p-2">
                        <i class="bi bi-exclamation-triangle fs-4 text-warning"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0 small text-uppercase fw-semibold">Late</h6>
                        <h3 class="mb-0 fw-bold">{{ $todayAttendance->late ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stats-card border-start border-info border-4">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stats-icon rounded-circle bg-info bg-opacity-10 p-2">
                        <i class="bi bi-calendar-x fs-4 text-info"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0 small text-uppercase fw-semibold">On Leave</h6>
                        <h3 class="mb-0 fw-bold">{{ $onLeave }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stats-card border-start border-secondary border-4">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stats-icon rounded-circle bg-secondary bg-opacity-10 p-2">
                        <i class="bi bi-question-circle fs-4 text-secondary"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0 small text-uppercase fw-semibold">Not Marked</h6>
                        <h3 class="mb-0 fw-bold">{{ $notMarked }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Weekly Attendance Trend --}}
    <div class="col-xl-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Weekly Attendance Trend</h5>
                <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-end gap-2" style="height: 200px;">
                    @foreach($weeklyTrend as $day)
                        <div class="flex-fill d-flex flex-column align-items-center justify-content-end h-100">
                            <span class="small text-muted mb-1">{{ $day['present'] }}</span>
                            <div class="w-100 rounded mb-1" style="height: {{ $day['present'] > 0 ? max(($day['present'] / max($totalEmployees, 1)) * 150, 4) : 4 }}px; background: var(--bs-success, #10b981); transition: height 0.3s;"></div>
                            @if($day['absent'] > 0)
                                <div class="w-100 rounded mb-1" style="height: {{ ($day['absent'] / max($totalEmployees, 1)) * 150 }}px; background: var(--bs-danger, #ef4444); transition: height 0.3s;"></div>
                            @endif
                            <span class="small fw-semibold mt-1">{{ $day['date'] }}</span>
                            <span class="small text-muted">{{ $day['label'] }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="d-flex gap-3 mt-3 pt-2 border-top">
                    <div class="d-flex align-items-center gap-1"><span class="d-inline-block rounded" style="width:12px;height:12px;background:var(--bs-success,#10b981)"></span><small class="text-muted">Present / Late</small></div>
                    <div class="d-flex align-items-center gap-1"><span class="d-inline-block rounded" style="width:12px;height:12px;background:var(--bs-danger,#ef4444)"></span><small class="text-muted">Absent</small></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Department Headcount --}}
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Department Headcount</h5>
            </div>
            <div class="card-body">
                @forelse($departmentStats as $dept)
                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="fw-semibold">{{ $dept['name'] }}</small>
                            <small class="text-muted">{{ $dept['count'] }}</small>
                        </div>
                        <div class="progress" style="height:6px;border-radius:4px;">
                            <div class="progress-bar bg-primary rounded" role="progressbar" style="width: {{ $maxDeptCount > 0 ? ($dept['count'] / $maxDeptCount) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0 small">No departments</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Pending Leave Requests --}}
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Pending Leave Requests</h5>
                <a href="{{ route('leaves.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                @if($pendingLeaves->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($pendingLeaves as $leave)
                            <div class="list-group-item px-3 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="small">{{ $leave->employee->full_name ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $leave->leaveType->name ?? 'Leave' }}
                                            &middot; {{ $leave->start_date->format('d/m') }} - {{ $leave->end_date->format('d/m') }}
                                            ({{ $leave->total_days }}d)</small>
                                    </div>
                                    <span class="badge bg-warning rounded-pill">Pending</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox fs-2 text-muted"></i>
                        <p class="text-muted mb-0 small mt-1">No pending requests</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- New Hires This Month --}}
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">New Hires This Month</h5>
                <a href="{{ route('employees.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                @if($newHires->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($newHires as $emp)
                            <div class="list-group-item px-3 py-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 text-success fw-bold" style="width:34px;height:34px;font-size:0.8rem;">
                                        {{ substr($emp->user->first_name ?? '?', 0, 1) }}{{ substr($emp->user->last_name ?? '', 0, 1) }}
                                    </span>
                                    <div class="flex-grow-1">
                                        <strong class="small">{{ $emp->full_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $emp->employee_code }} &middot; Joined {{ $emp->joining_date->format('d M') }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-person-plus fs-2 text-muted"></i>
                        <p class="text-muted mb-0 small mt-1">No new hires this month</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Upcoming Work Anniversaries --}}
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Upcoming Anniversaries</h5>
            </div>
            <div class="card-body p-0">
                @if($anniversaries->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($anniversaries as $emp)
                            <div class="list-group-item px-3 py-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10 text-warning fw-bold" style="width:34px;height:34px;font-size:0.8rem;">
                                        <i class="bi bi-gift"></i>
                                    </span>
                                    <div class="flex-grow-1">
                                        <strong class="small">{{ $emp->full_name }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $emp->joining_date->format('d M') }}
                                            &middot; {{ $emp->joining_date->diffInYears(today()) }} year{{ $emp->joining_date->diffInYears(today()) != 1 ? 's' : '' }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-gift fs-2 text-muted"></i>
                        <p class="text-muted mb-0 small mt-1">No anniversaries this month</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Employment Type Breakdown --}}
    <div class="col-xl-3">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Employment Types</h5>
            </div>
            <div class="card-body">
                @forelse($employmentTypes as $et)
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                        <span class="small">{{ $et['type'] }}</span>
                        <span class="badge bg-primary rounded-pill">{{ $et['count'] }}</span>
                    </div>
                @empty
                    <p class="text-muted mb-0 small">No data</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Upcoming Holidays --}}
    <div class="col-xl-3">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Upcoming Holidays</h5>
            </div>
            <div class="card-body p-0">
                @if($upcomingHolidays->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($upcomingHolidays as $holiday)
                            <div class="list-group-item px-3 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="small">{{ $holiday->name }}</strong>
                                        <br><small class="text-muted">{{ $holiday->type ?? 'Holiday' }}</small>
                                    </div>
                                    <span class="badge bg-light text-dark rounded-pill">{{ $holiday->date->format('d M') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-calendar-heart fs-2 text-muted"></i>
                        <p class="text-muted mb-0 small mt-1">No upcoming holidays</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Recent Announcements --}}
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Announcements</h5>
                <a href="{{ route('announcements.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @if($announcements->count() > 0)
                    @foreach($announcements as $announcement)
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <h6 class="mb-1">{{ $announcement->title }}</h6>
                                <span class="badge bg-{{ $announcement->priority === 'high' ? 'danger' : ($announcement->priority === 'medium' ? 'warning' : 'info') }} rounded-pill">{{ ucfirst($announcement->priority) }}</span>
                            </div>
                            <p class="text-muted small mb-1">{{ Str::limit($announcement->content, 200) }}</p>
                            <small class="text-muted">{{ $announcement->published_at?->format('d/m/Y H:i') ?? $announcement->created_at->diffForHumans() }}</small>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted mb-0 small">No announcements</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="row g-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('attendance.index') }}" class="btn btn-primary">
                        <i class="bi bi-clock"></i> Mark Attendance
                    </a>
                    <a href="{{ route('leaves.index') }}" class="btn btn-success">
                        <i class="bi bi-calendar-check"></i> Apply Leave
                    </a>
                    <a href="{{ route('employees.create') }}" class="btn btn-info text-white">
                        <i class="bi bi-person-plus"></i> Add Employee
                    </a>
                    <a href="{{ route('payroll.index') }}" class="btn btn-warning text-white">
                        <i class="bi bi-wallet2"></i> Process Payroll
                    </a>
                    <a href="{{ route('holidays.index') }}" class="btn btn-secondary">
                        <i class="bi bi-calendar3"></i> Manage Holidays
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.stats-card {
    border: none;
    border-radius: 12px;
    box-shadow: var(--card-shadow);
    transition: transform 0.2s, box-shadow 0.2s;
    background: var(--bs-body-bg, #fff);
}
.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}
.stats-icon {
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.list-group-item {
    border-left: none;
    border-right: none;
}
.card-header .card-title {
    font-size: 0.9rem;
}
.progress-bar {
    transition: width 0.6s ease;
}
</style>
@endpush
