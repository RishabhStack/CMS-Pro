<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Title</label>
                    <p class="mb-0 fs-6">{{ $announcement->title }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Type</label>
                    <p class="mb-0 fs-6">
                        @php
                            $badges = ['general' => 'secondary', 'holiday' => 'success', 'event' => 'info', 'policy' => 'warning'];
                            $badge = $badges[$announcement->type] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $badge }}">{{ ucfirst($announcement->type) }}</span>
                    </p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Priority</label>
                    <p class="mb-0 fs-6">
                        @php
                            $priorityBadges = ['high' => 'danger', 'medium' => 'warning', 'low' => 'info'];
                            $pBadge = $priorityBadges[$announcement->priority] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $pBadge }}">{{ ucfirst($announcement->priority) }}</span>
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Published At</label>
                    <p class="mb-0 fs-6">{{ $announcement->published_at ? $announcement->published_at->format('M d, Y h:i A') : '-' }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Expires At</label>
                    <p class="mb-0 fs-6">{{ $announcement->expires_at ? $announcement->expires_at->format('M d, Y h:i A') : '-' }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Status</label>
                    <p class="mb-0 fs-6">
                        @if($announcement->status === 'published')
                            <span class="badge bg-success">Published</span>
                        @else
                            <span class="badge bg-secondary">Draft</span>
                        @endif
                    </p>
                </div>
            </div>
            <div class="col-12">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Content</label>
                    <p class="mb-0 fs-6">{{ $announcement->content ?? '-' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase">Created By</label>
                    <p class="mb-0 fs-6">{{ $announcement->creator->full_name ?? $announcement->creator->first_name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
