<form action="{{ isset($holiday) ? route('holidays.update', $holiday->id) : route('holidays.store') }}" method="POST" id="holidayForm">
    @csrf
    @if(isset($holiday))
        @method('PUT')
    @endif
    <div class="mb-3">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $holiday->name ?? '') }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Date <span class="text-danger">*</span></label>
        <input type="date" name="date" class="form-control" value="{{ old('date', isset($holiday) && $holiday->date ? $holiday->date->format('Y-m-d') : '') }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Type</label>
        <select name="type" class="form-select">
            <option value="">Select Type</option>
            <option value="public" {{ (old('type', $holiday->type ?? '') === 'public') ? 'selected' : '' }}>Public</option>
            <option value="company" {{ (old('type', $holiday->type ?? '') === 'company') ? 'selected' : '' }}>Company</option>
            <option value="optional" {{ (old('type', $holiday->type ?? '') === 'optional') ? 'selected' : '' }}>Optional</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $holiday->description ?? '') }}</textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="active" {{ (old('status', $holiday->status ?? '') === 'active') ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ (old('status', $holiday->status ?? '') === 'inactive') ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">{{ isset($holiday) ? 'Update' : 'Create' }}</button>
    </div>
</form>
<script>
    App.form('#holidayForm', {
        success: function () {
            $('#globalModal').modal('hide');
            if (window.holidayTable) window.holidayTable.draw();
        }
    });
</script>