<form action="{{ isset($category) ? route('expense-categories.update', $category->id) : route('expense-categories.store') }}" method="POST" id="expenseCategoryForm">
    @csrf
    @if(isset($category))
        @method('PUT')
    @endif
    <div class="mb-3">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description ?? '') }}</textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Max Amount</label>
        <input type="number" step="0.01" min="0" name="max_amount" class="form-control" value="{{ old('max_amount', $category->max_amount ?? '') }}">
        <small class="text-muted">Leave empty for no limit</small>
    </div>
    <div class="mb-3">
        <label class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select" required>
            <option value="active" {{ (old('status', $category->status ?? '') === 'active') ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ (old('status', $category->status ?? '') === 'inactive') ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">{{ isset($category) ? 'Update' : 'Create' }}</button>
    </div>
</form>
<script>
    App.form('#expenseCategoryForm', {
        success: function () {
            $('#globalModal').modal('hide');
            if (window.expenseCategoryTable) window.expenseCategoryTable.draw();
        }
    });
</script>
