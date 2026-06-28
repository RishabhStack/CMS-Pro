<form action="{{ isset($announcement) ? route('announcements.update', $announcement->id) : route('announcements.store') }}" method="POST" id="announcementForm">
    @csrf
    @if(isset($announcement))
        @method('PUT')
    @endif
    <div class="mb-3">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $announcement->title ?? '') }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Content</label>
        <textarea name="content" class="form-control" rows="4">{{ old('content', $announcement->content ?? '') }}</textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Type</label>
        <select name="type" class="form-select">
            <option value="general" {{ (old('type', $announcement->type ?? '') === 'general') ? 'selected' : '' }}>General</option>
            <option value="holiday" {{ (old('type', $announcement->type ?? '') === 'holiday') ? 'selected' : '' }}>Holiday</option>
            <option value="event" {{ (old('type', $announcement->type ?? '') === 'event') ? 'selected' : '' }}>Event</option>
            <option value="policy" {{ (old('type', $announcement->type ?? '') === 'policy') ? 'selected' : '' }}>Policy</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Priority</label>
        <select name="priority" class="form-select">
            <option value="low" {{ (old('priority', $announcement->priority ?? '') === 'low') ? 'selected' : '' }}>Low</option>
            <option value="medium" {{ (old('priority', $announcement->priority ?? '') === 'medium') ? 'selected' : '' }}>Medium</option>
            <option value="high" {{ (old('priority', $announcement->priority ?? '') === 'high') ? 'selected' : '' }}>High</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Published At</label>
        <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at', isset($announcement) && $announcement->published_at ? $announcement->published_at->format('Y-m-d\TH:i') : '') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Expires At</label>
        <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at', isset($announcement) && $announcement->expires_at ? $announcement->expires_at->format('Y-m-d\TH:i') : '') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="draft" {{ (old('status', $announcement->status ?? '') === 'draft') ? 'selected' : '' }}>Draft</option>
            <option value="published" {{ (old('status', $announcement->status ?? '') === 'published') ? 'selected' : '' }}>Published</option>
        </select>
    </div>
    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">{{ isset($announcement) ? 'Update' : 'Create' }}</button>
    </div>
</form>
<script>
    App.form('#announcementForm', {
        success: function () {
            $('#globalModal').modal('hide');
            if (window.announcementTable) window.announcementTable.draw();
        }
    });
</script>