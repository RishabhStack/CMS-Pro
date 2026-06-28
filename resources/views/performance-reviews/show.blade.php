<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="fw-semibold text-muted small text-uppercase">Employee</label>
                <p class="mb-0 fs-6">{{ $review->employee->full_name ?? 'N/A' }}</p>
            </div>
            <div class="col-md-6">
                <label class="fw-semibold text-muted small text-uppercase">Reviewer</label>
                <p class="mb-0 fs-6">{{ $review->reviewer?->first_name ?? 'Not assigned' }} {{ $review->reviewer?->last_name ?? '' }}</p>
            </div>
            <div class="col-md-4">
                <label class="fw-semibold text-muted small text-uppercase">Review Period</label>
                <p class="mb-0 fs-6">{{ $review->review_period }}</p>
            </div>
            <div class="col-md-4">
                <label class="fw-semibold text-muted small text-uppercase">Period</label>
                <p class="mb-0 fs-6">{{ $review->start_date->format('M d, Y') }} - {{ $review->end_date->format('M d, Y') }}</p>
            </div>
            <div class="col-md-4">
                <label class="fw-semibold text-muted small text-uppercase">Due Date</label>
                <p class="mb-0 fs-6">{{ $review->due_date ? $review->due_date->format('M d, Y') : '-' }}</p>
            </div>
            <div class="col-md-4">
                <label class="fw-semibold text-muted small text-uppercase">Overall Rating</label>
                <p class="mb-0 fs-6">{{ $review->overall_rating ?? '-' }}</p>
            </div>
            <div class="col-md-4">
                <label class="fw-semibold text-muted small text-uppercase">Goals Count</label>
                <p class="mb-0 fs-6">{{ $review->goals_count ?? $review->goals->count() }}</p>
            </div>
            <div class="col-md-4">
                <label class="fw-semibold text-muted small text-uppercase">Status</label>
                <p class="mb-0 fs-6">
                    @php
                        $badges = ['draft' => 'secondary', 'pending_review' => 'warning', 'completed' => 'success', 'cancelled' => 'danger'];
                    @endphp
                    <span class="badge bg-{{ $badges[$review->status] ?? 'secondary' }}">{{ str_replace('_', ' ', ucfirst($review->status)) }}</span>
                </p>
            </div>
            @if($review->employee_notes)
                <div class="col-12">
                    <label class="fw-semibold text-muted small text-uppercase">Employee Notes</label>
                    <p class="mb-0 fs-6">{{ $review->employee_notes }}</p>
                </div>
            @endif
            @if($review->reviewer_notes)
                <div class="col-12">
                    <label class="fw-semibold text-muted small text-uppercase">Reviewer Notes</label>
                    <p class="mb-0 fs-6">{{ $review->reviewer_notes }}</p>
                </div>
            @endif
        </div>

        @if($review->goals->count() > 0)
            <hr>
            <h6 class="fw-semibold mb-3">Goals</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Target</th>
                            <th>Achieved</th>
                            <th>Weight</th>
                            <th>Self</th>
                            <th>Manager</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($review->goals as $goal)
                            <tr>
                                <td>{{ $goal->title }}</td>
                                <td><span class="badge bg-info">{{ ucfirst($goal->category) }}</span></td>
                                <td>{{ $goal->target_value ?? '-' }}</td>
                                <td>{{ $goal->achieved_value ?? '-' }}</td>
                                <td>{{ $goal->weight }}%</td>
                                <td>{{ $goal->self_rating ?? '-' }}</td>
                                <td>{{ $goal->manager_rating ?? '-' }}</td>
                                <td>
                                    @php
                                        $gBadges = ['not_started' => 'secondary', 'in_progress' => 'warning', 'achieved' => 'success', 'not_achieved' => 'danger'];
                                    @endphp
                                    <span class="badge bg-{{ $gBadges[$goal->status] ?? 'secondary' }}">{{ str_replace('_', ' ', ucfirst($goal->status)) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if($review->feedbacks->count() > 0)
            <hr>
            <h6 class="fw-semibold mb-3">Feedback</h6>
            @foreach($review->feedbacks as $feedback)
                <div class="border rounded p-3 mb-2">
                    <div class="d-flex justify-content-between">
                        <strong>{{ $feedback->is_anonymous ? 'Anonymous' : ($feedback->reviewer->first_name ?? 'N/A') }}</strong>
                        <span class="badge bg-primary">{{ $feedback->rating }}/5</span>
                    </div>
                    <p class="mb-0 mt-1">{{ $feedback->comment }}</p>
                    <small class="text-muted">{{ $feedback->submitted_at ? $feedback->submitted_at->format('M d, Y h:i A') : '' }}</small>
                </div>
            @endforeach
        @endif

        <div class="text-end mt-3">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
</div>
