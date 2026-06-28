<form action="{{ isset($expense) ? route('expenses.update', $expense->id) : route('expenses.store') }}" method="POST" id="expenseForm" enctype="multipart/form-data">
    @csrf
    @if(isset($expense))
        @method('PUT')
    @endif
    <div class="mb-3">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-select">
            <option value="">Select Category</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ (old('category_id', $expense->category_id ?? '') == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Expense Date <span class="text-danger">*</span></label>
        <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', isset($expense) ? $expense->expense_date->format('Y-m-d') : '') }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Amount <span class="text-danger">*</span></label>
        <input type="number" step="0.01" min="0" name="amount" class="form-control" value="{{ old('amount', $expense->amount ?? '') }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Description <span class="text-danger">*</span></label>
        <textarea name="description" class="form-control" rows="3" required>{{ old('description', $expense->description ?? '') }}</textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Receipt</label>
        <input type="file" name="receipt" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
        @if(isset($expense) && $expense->receipt_original_name)
            <small class="text-muted">Current file: {{ $expense->receipt_original_name }}</small>
        @endif
    </div>
    <div class="mb-3">
        <label class="form-label">Notes</label>
        <textarea name="notes" class="form-control" rows="2">{{ old('notes', $expense->notes ?? '') }}</textarea>
    </div>
    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">{{ isset($expense) ? 'Update' : 'Submit' }}</button>
    </div>
</form>
<script>
    App.form('#expenseForm', {
        success: function () {
            $('#globalModal').modal('hide');
            if (window.expensesTable) window.expensesTable.draw();
        }
    });
</script>
