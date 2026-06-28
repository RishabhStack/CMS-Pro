<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Name</label>
                    <p class="mb-0 fs-6">{{ $department->name }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Slug</label>
                    <p class="mb-0 fs-6">{{ $department->slug ?? '-' }}</p>
                </div>
            </div>
            <div class="col-12">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Description</label>
                    <p class="mb-0 fs-6">{{ $department->description ?? '-' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Manager</label>
                    <p class="mb-0 fs-6">{{ $department->manager->full_name ?? $department->manager->user->first_name ?? '-' }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Designations</label>
                    <p class="mb-0 fs-6">{{ $department->designations->count() ?? 0 }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Employees</label>
                    <p class="mb-0 fs-6">{{ $department->employees->count() ?? 0 }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Status</label>
                    <p class="mb-0 fs-6">
                        @if($department->status === 'active')
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
