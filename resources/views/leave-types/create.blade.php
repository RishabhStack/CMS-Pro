<form action="{{ isset($leaveType) ? route('leave-types.update', $leaveType->id) : route('leave-types.store') }}" method="POST" id="leaveTypeForm">
    @csrf
    @if(isset($leaveType))
        @method('PUT')
    @endif
    <div class="mb-3">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $leaveType->name ?? '') }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Slug</label>
        <input type="text" name="slug" class="form-control" value="{{ old('slug', $leaveType->slug ?? '') }}" readonly>
        <small class="text-muted">Auto-generated from name</small>
    </div>
    <div class="mb-3">
        <label class="form-label">Days Per Year</label>
        <input type="number" name="days_per_year" class="form-control" value="{{ old('days_per_year', $leaveType->days_per_year ?? '') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Carry Forward</label>
        <select name="carry_forward" class="form-select">
            <option value="0" {{ (old('carry_forward', $leaveType->carry_forward ?? '0') == 0) ? 'selected' : '' }}>No</option>
            <option value="1" {{ (old('carry_forward', $leaveType->carry_forward ?? '') == 1) ? 'selected' : '' }}>Yes</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Max Carry Forward</label>
        <input type="number" name="max_carry_forward" class="form-control" value="{{ old('max_carry_forward', $leaveType->max_carry_forward ?? '') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Color</label>
        <input type="color" name="color" class="form-control form-control-color" value="{{ old('color', $leaveType->color ?? '#0d6efd') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $leaveType->description ?? '') }}</textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="active" {{ (old('status', $leaveType->status ?? '') === 'active') ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ (old('status', $leaveType->status ?? '') === 'inactive') ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">{{ isset($leaveType) ? 'Update' : 'Create' }}</button>
    </div>
</form>
<script>
    App.form('#leaveTypeForm', {
        success: function () {
            $('#globalModal').modal('hide');
            if (window.leaveTypeTable) window.leaveTypeTable.draw();
        }
    });
</script>