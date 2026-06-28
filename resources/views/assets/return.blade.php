<form action="{{ route('assets.return', $asset->id) }}" method="POST" id="returnAssetForm">
    @csrf
    <p class="mb-3">Returning: <strong>{{ $asset->name }}</strong></p>
    @if($asset->currentAssignment)
        <p class="text-muted small mb-3">
            Currently assigned to <strong>{{ $asset->currentAssignment->employee->full_name ?? 'Unknown' }}</strong>
            since {{ $asset->currentAssignment->assigned_at ? $asset->currentAssignment->assigned_at->format('d/m/Y') : 'Unknown' }}
        </p>
    @endif
    <div class="mb-3">
        <label class="form-label">Condition on Return</label>
        <textarea name="condition_on_return" class="form-control" rows="2" placeholder="e.g., Good condition, fully functional"></textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Notes</label>
        <textarea name="notes" class="form-control" rows="2"></textarea>
    </div>
    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-warning">Return Asset</button>
    </div>
</form>
<script>
    App.form('#returnAssetForm', {
        success: function () {
            $('#globalModal').modal('hide');
            if (window.assetsTable) window.assetsTable.draw();
        }
    });
</script>
