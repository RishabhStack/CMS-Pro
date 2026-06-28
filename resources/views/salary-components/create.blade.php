<form action="{{ isset($component) ? route('salary-components.update', $component->id) : route('salary-components.store') }}" method="POST" id="salaryComponentForm">
    @csrf
    @if(isset($component))
        @method('PUT')
    @endif
    <div class="mb-3">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $component->name ?? '') }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Slug</label>
        <input type="text" name="slug" class="form-control" value="{{ old('slug', $component->slug ?? '') }}" readonly>
        <small class="text-muted">Auto-generated from name</small>
    </div>
    <div class="mb-3">
        <label class="form-label">Type</label>
        <select name="type" class="form-select">
            <option value="earning" {{ (old('type', $component->type ?? '') === 'earning') ? 'selected' : '' }}>Earning</option>
            <option value="deduction" {{ (old('type', $component->type ?? '') === 'deduction') ? 'selected' : '' }}>Deduction</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Value Type</label>
        <select name="value_type" class="form-select">
            <option value="fixed" {{ (old('value_type', $component->value_type ?? '') === 'fixed') ? 'selected' : '' }}>Fixed Amount</option>
            <option value="percentage" {{ (old('value_type', $component->value_type ?? '') === 'percentage') ? 'selected' : '' }}>Percentage (%)</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Default Value</label>
        <input type="number" step="0.01" name="default_value" class="form-control" value="{{ old('default_value', $component->default_value ?? '') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $component->description ?? '') }}</textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="active" {{ (old('status', $component->status ?? '') === 'active') ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ (old('status', $component->status ?? '') === 'inactive') ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">{{ isset($component) ? 'Update' : 'Create' }}</button>
    </div>
</form>
<script>
    App.form('#salaryComponentForm', {
        success: function () {
            $('#globalModal').modal('hide');
            if (window.salaryComponentTable) window.salaryComponentTable.draw();
        }
    });
</script>