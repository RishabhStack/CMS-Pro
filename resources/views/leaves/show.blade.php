<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Employee</label>
                    <p class="mb-0 fs-6">{{ $leave->employee->full_name ?? '-' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Leave Type</label>
                    <p class="mb-0 fs-6">{{ $leave->leaveType->name ?? '-' }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Start Date</label>
                    <p class="mb-0 fs-6">{{ $leave->start_date ? $leave->start_date->format('M d, Y') : '-' }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">End Date</label>
                    <p class="mb-0 fs-6">{{ $leave->end_date ? $leave->end_date->format('M d, Y') : '-' }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Total Days</label>
                    <p class="mb-0 fs-6">{{ $leave->total_days }}</p>
                </div>
            </div>
            <div class="col-12">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Reason</label>
                    <p class="mb-0 fs-6">{{ $leave->reason ?? '-' }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Status</label>
                    <p class="mb-0 fs-6">
                        @php
                            $badges = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger', 'cancelled' => 'secondary'];
                            $badge = $badges[$leave->status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $badge }}">{{ ucfirst($leave->status) }}</span>
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Approved By</label>
                    <p class="mb-0 fs-6">{{ $leave->approvedBy?->full_name ?? $leave->approvedBy?->first_name ?? '-' }}</p>
                </div>
            </div>
            @if($leave->rejection_reason)
            <div class="col-12">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Rejection Reason</label>
                    <p class="mb-0 fs-6">{{ $leave->rejection_reason }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
