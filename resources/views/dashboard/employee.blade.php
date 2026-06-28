@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card border-start border-primary border-4 h-100">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stats-icon rounded-circle bg-primary bg-opacity-10 p-2">
                        <i class="bi bi-person-badge fs-4 text-primary"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0 small text-uppercase fw-semibold">My Status</h6>
                        <h5 class="mb-0 fw-bold">{{ $employee?->status ?? 'N/A' }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card border-start border-success border-4 h-100">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stats-icon rounded-circle bg-success bg-opacity-10 p-2">
                        <i class="bi bi-check-circle fs-4 text-success"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0 small text-uppercase fw-semibold">Today's Attendance</h6>
                        <h5 class="mb-0 fw-bold">
                            @if($myAttendance)
                                <span class="badge bg-{{ $myAttendance->status === 'present' ? 'success' : ($myAttendance->status === 'late' ? 'warning' : 'danger') }} fs-6">
                                    {{ ucfirst($myAttendance->status) }}
                                </span>
                            @else
                                <span class="badge bg-secondary fs-6">Not Marked</span>
                            @endif
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card border-start border-warning border-4 h-100">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stats-icon rounded-circle bg-warning bg-opacity-10 p-2">
                        <i class="bi bi-calendar-check fs-4 text-warning"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0 small text-uppercase fw-semibold">Upcoming Holidays</h6>
                        <h5 class="mb-0 fw-bold">{{ $upcomingHolidays->count() }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card border-start border-info border-4 h-100">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stats-icon rounded-circle bg-info bg-opacity-10 p-2">
                        <i class="bi bi-megaphone fs-4 text-info"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0 small text-uppercase fw-semibold">Announcements</h6>
                        <h5 class="mb-0 fw-bold">{{ $announcements->count() }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Company-wide Today's Overview --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bi bi-people me-1"></i> Today's Company Overview</h5>
            </div>
            <div class="card-body py-3">
                <div class="row g-3 text-center">
                    <div class="col">
                        <div class="p-2">
                            <h6 class="text-muted small text-uppercase fw-semibold mb-1">Present</h6>
                            <h4 class="mb-0 text-success fw-bold">{{ $todayStat->present ?? 0 }}</h4>
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-2">
                            <h6 class="text-muted small text-uppercase fw-semibold mb-1">Late</h6>
                            <h4 class="mb-0 text-warning fw-bold">{{ $todayStat->late ?? 0 }}</h4>
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-2">
                            <h6 class="text-muted small text-uppercase fw-semibold mb-1">Half Day</h6>
                            <h4 class="mb-0 text-info fw-bold">{{ $todayStat->half_day ?? 0 }}</h4>
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-2">
                            <h6 class="text-muted small text-uppercase fw-semibold mb-1">On Leave</h6>
                            <h4 class="mb-0 text-primary fw-bold">{{ $todayOnLeave }}</h4>
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-2">
                            <h6 class="text-muted small text-uppercase fw-semibold mb-1">Absent</h6>
                            <h4 class="mb-0 text-danger fw-bold">{{ $todayStat->absent ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">My Recent Leaves</h5>
                <a href="{{ route('leaves.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @if($myLeaves->count())
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Dates</th>
                                    <th>Days</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($myLeaves as $leave)
                                    <tr>
                                        <td>{{ $leave->leaveType->name ?? '-' }}</td>
                                        <td>{{ $leave->start_date->format('d/m') }} - {{ $leave->end_date->format('d/m') }}</td>
                                        <td>{{ $leave->total_days }}</td>
                                        <td>
                                            @php
                                                $badges = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger', 'cancelled' => 'secondary'];
                                            @endphp
                                            <span class="badge bg-{{ $badges[$leave->status] ?? 'secondary' }}">{{ ucfirst($leave->status) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No leave records found.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Upcoming Holidays</h5>
            </div>
            <div class="card-body">
                @if($upcomingHolidays->count())
                    <div class="list-group list-group-flush">
                        @foreach($upcomingHolidays as $holiday)
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $holiday->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $holiday->type }}</small>
                                </div>
                                <span class="badge bg-info rounded-pill">{{ $holiday->date->format('d M, Y') }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No upcoming holidays.</p>
                @endif
            </div>
        </div>
    </div>

    @if($myUpcomingLeaves->count())
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Upcoming Approved Leaves</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($myUpcomingLeaves as $leave)
                            <div class="col-md-4">
                                <div class="border rounded p-3">
                                    <h6 class="mb-1">{{ $leave->leaveType->name ?? 'Leave' }}</h6>
                                    <small class="text-muted">{{ $leave->start_date->format('d M, Y') }} - {{ $leave->end_date->format('d M, Y') }}</small>
                                    <br>
                                    <span class="badge bg-success">{{ $leave->total_days }} day(s)</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($announcements->count())
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Announcements</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($announcements as $announcement)
                            <div class="col-md-6">
                                <div class="border rounded p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-0">{{ $announcement->title }}</h6>
                                        @php
                                            $pBadges = ['high' => 'danger', 'medium' => 'warning', 'low' => 'info'];
                                        @endphp
                                        <span class="badge bg-{{ $pBadges[$announcement->priority] ?? 'secondary' }}">{{ ucfirst($announcement->priority) }}</span>
                                    </div>
                                    <p class="text-muted small mb-1">{{ Str::limit($announcement->content, 150) }}</p>
                                    <small class="text-muted">{{ $announcement->published_at?->format('d M, Y') }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function ucfirst(str) {
        if (!str) return '';
        str = String(str);
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
</script>
@endpush