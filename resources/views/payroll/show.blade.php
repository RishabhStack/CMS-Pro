<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Employee</label>
                    <p class="mb-0 fs-6">{{ $payroll->employee->full_name ?? '-' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Pay Period</label>
                    <p class="mb-0 fs-6">{{ date('F', mktime(0, 0, 0, $payroll->month, 1)) }} {{ $payroll->year }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Basic Salary</label>
                    <p class="mb-0 fs-6">{{ number_format($payroll->basic_salary, 2) }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Total Earnings</label>
                    <p class="mb-0 fs-6">{{ number_format($payroll->total_earnings, 2) }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Total Deductions</label>
                    <p class="mb-0 fs-6">{{ number_format($payroll->total_deductions, 2) }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Net Salary</label>
                    <p class="mb-0 fs-6 fw-bold">{{ number_format($payroll->net_salary, 2) }}</p>
                </div>
            </div>
            <div class="col-12">
                <hr class="my-1">
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Working Days</label>
                    <p class="mb-0 fs-6">{{ $payroll->working_days ?? '-' }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Present Days</label>
                    <p class="mb-0 fs-6">{{ $payroll->present_days ?? '-' }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Absent Days</label>
                    <p class="mb-0 fs-6">{{ $payroll->absent_days ?? '-' }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Leave Days</label>
                    <p class="mb-0 fs-6">{{ $payroll->leave_days ?? '-' }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Status</label>
                    <p class="mb-0 fs-6">
                        @php
                            $badges = ['generated' => 'success', 'processing' => 'warning', 'paid' => 'info', 'cancelled' => 'danger'];
                            $badge = $badges[$payroll->status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $badge }}">{{ ucfirst($payroll->status) }}</span>
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Payment Method</label>
                    <p class="mb-0 fs-6">{{ $payroll->payment_method ?? '-' }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Transaction ID</label>
                    <p class="mb-0 fs-6">{{ $payroll->transaction_id ?? '-' }}</p>
                </div>
            </div>
            @if($payroll->paid_at)
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Paid At</label>
                    <p class="mb-0 fs-6">{{ $payroll->paid_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
            @endif
            <div class="col-12">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Notes</label>
                    <p class="mb-0 fs-6">{{ $payroll->notes ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
