<form action="{{ isset($department) ? route('departments.update', $department->id) : route('departments.store') }}" method="POST" id="departmentForm">
    @csrf
    @if(isset($department))
        @method('PUT')
    @endif
    <div class="mb-3">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $department->name ?? '') }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Slug</label>
        <input type="text" name="slug" class="form-control" value="{{ old('slug', $department->slug ?? '') }}" readonly>
        <small class="text-muted">Auto-generated from name</small>
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $department->description ?? '') }}</textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="active" {{ (old('status', $department->status ?? '') === 'active') ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ (old('status', $department->status ?? '') === 'inactive') ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">{{ isset($department) ? 'Update' : 'Create' }}</button>
    </div>
</form>
<script>
    App.form('#departmentForm', {
        success: function () {
            $('#globalModal').modal('hide');
            if (window.departmentTable) window.departmentTable.draw();
        }
    });
</script>