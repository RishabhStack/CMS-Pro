<form action="{{ route('assets.assign', $asset->id) }}" method="POST" id="assignAssetForm">
    @csrf
    <p class="mb-3">Assigning: <strong>{{ $asset->name }}</strong> ({{ $asset->serial_number ?? 'No S/N' }})</p>
    <div class="mb-3">
        <label class="form-label">Employee <span class="text-danger">*</span></label>
        <select name="employee_id" class="form-select" required>
            <option value="">Select Employee</option>
            @foreach($employees as $emp)
                <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Expected Return Date</label>
        <input type="date" name="expected_return_date" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Condition on Handover</label>
        <textarea name="condition_on_handover" class="form-control" rows="2" placeholder="e.g., Good condition, minor scratches"></textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Notes</label>
        <textarea name="notes" class="form-control" rows="2"></textarea>
    </div>
    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Assign Asset</button>
    </div>
</form>
<script>
    App.form('#assignAssetForm', {
        success: function () {
            $('#globalModal').modal('hide');
            if (window.assetsTable) window.assetsTable.draw();
        }
    });
</script>
