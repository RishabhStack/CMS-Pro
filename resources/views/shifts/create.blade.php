<form action="{{ isset($shift) ? route('shifts.update', $shift->id) : route('shifts.store') }}" method="POST" id="shiftForm">
    @csrf
    @if(isset($shift))
        @method('PUT')
    @endif
    <div class="mb-3">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $shift->name ?? '') }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Slug</label>
        <input type="text" name="slug" class="form-control" value="{{ old('slug', $shift->slug ?? '') }}" readonly>
        <small class="text-muted">Auto-generated from name</small>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Start Time <span class="text-danger">*</span></label>
            <input type="time" name="start_time" class="form-control" value="{{ old('start_time', $shift->start_time ?? '') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">End Time <span class="text-danger">*</span></label>
            <input type="time" name="end_time" class="form-control" value="{{ old('end_time', $shift->end_time ?? '') }}" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label">Grace Minutes</label>
            <input type="number" name="grace_minutes" class="form-control" value="{{ old('grace_minutes', $shift->grace_minutes ?? 0) }}" min="0">
        </div>
        <div class="col-md-4">
            <label class="form-label">Half Day Cutoff</label>
            <input type="time" name="half_day_cutoff" class="form-control" value="{{ old('half_day_cutoff', $shift->half_day_cutoff ?? '') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Color</label>
            <input type="color" name="color" class="form-control form-control-color" value="{{ old('color', $shift->color ?? '#0d6efd') }}">
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $shift->description ?? '') }}</textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="active" {{ (old('status', $shift->status ?? '') === 'active') ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ (old('status', $shift->status ?? '') === 'inactive') ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">{{ isset($shift) ? 'Update' : 'Create' }}</button>
    </div>
</form>
<script>
    App.form('#shiftForm', {
        success: function () {
            $('#globalModal').modal('hide');
            if (window.shiftTable) window.shiftTable.draw();
        }
    });
</script>
