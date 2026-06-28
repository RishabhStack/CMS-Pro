<form action="{{ isset($role) ? route('roles.update', $role->id) : route('roles.store') }}" method="POST" id="roleForm">
    @csrf
    @if(isset($role))
        @method('PUT')
    @endif
    <div class="mb-3">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $role->name ?? '') }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Slug</label>
        <input type="text" name="slug" class="form-control" value="{{ old('slug', $role->slug ?? '') }}" readonly>
        <small class="text-muted">Auto-generated from name</small>
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $role->description ?? '') }}</textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="active" {{ (old('status', $role->status ?? '') === 'active') ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ (old('status', $role->status ?? '') === 'inactive') ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">{{ isset($role) ? 'Update' : 'Create' }}</button>
    </div>
</form>
<script>
    App.form('#roleForm', {
        success: function () {
            $('#globalModal').modal('hide');
            if (window.roleTable) window.roleTable.draw();
        }
    });
</script>