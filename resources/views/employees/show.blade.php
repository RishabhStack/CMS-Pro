<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Employee Code</label>
                    <p class="mb-0 fs-6">{{ $employee->employee_code }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Full Name</label>
                    <p class="mb-0 fs-6">{{ $employee->full_name }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Email</label>
                    <p class="mb-0 fs-6">{{ $employee->email }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Phone</label>
                    <p class="mb-0 fs-6">{{ $employee->user->phone ?? '-' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Department</label>
                    <p class="mb-0 fs-6">{{ $employee->department->name ?? '-' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Designation</label>
                    <p class="mb-0 fs-6">{{ $employee->designation->name ?? '-' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Employment Type</label>
                    <p class="mb-0 fs-6">{{ ucfirst($employee->employment_type) ?? '-' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Joining Date</label>
                    <p class="mb-0 fs-6">{{ $employee->joining_date ? $employee->joining_date->format('M d, Y') : '-' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Reporting To</label>
                    <p class="mb-0 fs-6">{{ $employee->reportingTo->full_name ?? '-' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Work Location</label>
                    <p class="mb-0 fs-6">{{ $employee->work_location ?? '-' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Work Shift</label>
                    <p class="mb-0 fs-6">{{ $employee->work_shift ?? '-' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Status</label>
                    <p class="mb-0 fs-6">
                        @php
                            $badges = ['active' => 'success', 'inactive' => 'secondary', 'terminated' => 'danger', 'resigned' => 'warning'];
                            $badge = $badges[$employee->status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $badge }}">{{ ucfirst($employee->status) }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
