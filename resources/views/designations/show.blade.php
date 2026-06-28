<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Name</label>
                    <p class="mb-0 fs-6">{{ $designation->name }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Slug</label>
                    <p class="mb-0 fs-6">{{ $designation->slug ?? '-' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Department</label>
                    <p class="mb-0 fs-6">{{ $designation->department->name ?? '-' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Employees</label>
                    <p class="mb-0 fs-6">{{ $designation->employees_count ?? $designation->employees->count() ?? 0 }}</p>
                </div>
            </div>
            <div class="col-12">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Description</label>
                    <p class="mb-0 fs-6">{{ $designation->description ?? '-' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Status</label>
                    <p class="mb-0 fs-6">
                        @if($designation->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
