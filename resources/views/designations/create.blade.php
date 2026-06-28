<form action="{{ isset($designation) ? route('designations.update', $designation->id) : route('designations.store') }}" method="POST" id="designationForm">
    @csrf
    @if(isset($designation))
        @method('PUT')
    @endif
    <div class="mb-3">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $designation->name ?? '') }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Slug</label>
        <input type="text" name="slug" class="form-control" value="{{ old('slug', $designation->slug ?? '') }}" readonly>
        <small class="text-muted">Auto-generated from name</small>
    </div>
    <div class="mb-3">
        <label class="form-label">Department</label>
        <select name="department_id" class="form-select">
            <option value="">Select Department</option>
            @foreach($departments ?? [] as $dept)
                <option value="{{ $dept->id }}" {{ (old('department_id', $designation->department_id ?? '') == $dept->id) ? 'selected' : '' }}>{{ $dept->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $designation->description ?? '') }}</textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="active" {{ (old('status', $designation->status ?? '') === 'active') ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ (old('status', $designation->status ?? '') === 'inactive') ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">{{ isset($designation) ? 'Update' : 'Create' }}</button>
    </div>
</form>
<script>
    App.form('#designationForm', {
        success: function () {
            $('#globalModal').modal('hide');
            if (window.designationTable) window.designationTable.draw();
        }
    });
</script>