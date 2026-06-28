@php
    $isAdmin = auth()->user()->hasRole(['Owner', 'Admin']);
@endphp

<div class="mb-3">
    <h6 class="fw-bold">{{ $ticket->subject }}</h6>
    <span class="badge bg-{{ ['open'=>'success','in_progress'=>'info','resolved'=>'primary','closed'=>'secondary'][$ticket->status] ?? 'secondary' }}">{{ str_replace('_', ' ', ucfirst($ticket->status)) }}</span>
    <span class="badge bg-{{ ['low'=>'secondary','medium'=>'info','high'=>'warning','critical'=>'danger'][$ticket->priority] ?? 'secondary' }} ms-1">{{ ucfirst($ticket->priority) }}</span>
    <span class="badge bg-secondary ms-1">{{ ucfirst($ticket->category) }}</span>
</div>

<div class="table-responsive mb-3">
    <table class="table table-bordered table-sm">
        <tr>
            <th class="w-25">Ticket#</th>
            <td>{{ $ticket->ticket_number }}</td>
        </tr>
        <tr>
            <th>Reported By</th>
            <td>{{ $ticket->employee->full_name }}</td>
        </tr>
        <tr>
            <th>Assigned To</th>
            <td>{{ $ticket->assignee?->first_name }} {{ $ticket->assignee?->last_name ?? 'Unassigned' }}</td>
        </tr>
        @if($ticket->resolved_at)
        <tr>
            <th>Resolved At</th>
            <td>{{ $ticket->resolved_at->format('d/m/Y H:i') }}</td>
        </tr>
        @endif
        @if($ticket->closed_at)
        <tr>
            <th>Closed At</th>
            <td>{{ $ticket->closed_at->format('d/m/Y H:i') }}</td>
        </tr>
        @endif
    </table>
</div>

<div class="mb-3">
    <h6>Description</h6>
    <p class="mb-0">{{ nl2br(e($ticket->description)) }}</p>
</div>

<hr>
<h6 class="mb-3">Comments</h6>

<div class="comments-list mb-3" style="max-height: 300px; overflow-y: auto;">
    @forelse($ticket->comments as $comment)
    <div class="border rounded p-2 mb-2 {{ $comment->is_internal ? 'bg-warning-subtle border-warning' : '' }}">
        <div class="d-flex justify-content-between">
            <strong>{{ $comment->user?->first_name }} {{ $comment->user?->last_name }}</strong>
            <small class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</small>
        </div>
        <p class="mb-0 mt-1">{{ nl2br(e($comment->comment)) }}</p>
        @if($comment->is_internal)
        <small class="text-warning"><i class="bi bi-lock"></i> Internal Note</small>
        @endif
    </div>
    @empty
    <p class="text-muted">No comments yet.</p>
    @endforelse
</div>

<form id="commentForm">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="mb-2">
        <textarea name="comment" class="form-control" rows="2" placeholder="Add a comment..." required></textarea>
    </div>
    @if($isAdmin)
    <div class="form-check mb-2">
        <input type="checkbox" name="is_internal" class="form-check-input" id="isInternal">
        <label class="form-check-label" for="isInternal">Internal Note</label>
    </div>
    @endif
    <button type="submit" class="btn btn-primary btn-sm">Post Comment</button>
</form>

@push('scripts')
<script>
    $(document).off('submit', '#commentForm').on('submit', '#commentForm', function (e) {
        e.preventDefault();
        const formData = $(this).serialize();
        $.ajax({
            url: '{{ url("tickets") }}/' + {{ $ticket->id }} + '/comment',
            type: 'POST',
            data: formData,
            success: function (res) {
                App.toast(res?.message || 'Comment added successfully', 'success');
                setTimeout(function () { location.reload(); }, 800);
            },
            error: function (xhr) {
                App.toast(xhr?.responseJSON?.message || 'Failed to add comment', 'error');
            }
        });
    });
</script>
@endpush
