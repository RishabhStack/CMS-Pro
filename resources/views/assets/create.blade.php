<form action="{{ isset($asset) ? route('assets.update', $asset->id) : route('assets.store') }}" method="POST" id="assetForm" enctype="multipart/form-data">
    @csrf
    @if(isset($asset))
        @method('PUT')
    @endif
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $asset->name ?? '') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Type <span class="text-danger">*</span></label>
            <select name="type" class="form-select" required>
                <option value="laptop" {{ (old('type', $asset->type ?? '') === 'laptop') ? 'selected' : '' }}>Laptop</option>
                <option value="phone" {{ (old('type', $asset->type ?? '') === 'phone') ? 'selected' : '' }}>Phone</option>
                <option value="accessory" {{ (old('type', $asset->type ?? '') === 'accessory') ? 'selected' : '' }}>Accessory</option>
                <option value="other" {{ (old('type', $asset->type ?? '') === 'other') ? 'selected' : '' }}>Other</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Serial Number</label>
            <input type="text" name="serial_number" class="form-control" value="{{ old('serial_number', $asset->serial_number ?? '') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Brand</label>
            <input type="text" name="brand" class="form-control" value="{{ old('brand', $asset->brand ?? '') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Model</label>
            <input type="text" name="model" class="form-control" value="{{ old('model', $asset->model ?? '') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Purchase Date</label>
            <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date', isset($asset) && $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : '') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Purchase Cost</label>
            <input type="number" step="0.01" min="0" name="purchase_cost" class="form-control" value="{{ old('purchase_cost', $asset->purchase_cost ?? '') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Warranty Expiry</label>
            <input type="date" name="warranty_expiry" class="form-control" value="{{ old('warranty_expiry', isset($asset) && $asset->warranty_expiry ? $asset->warranty_expiry->format('Y-m-d') : '') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Status <span class="text-danger">*</span></label>
            <select name="status" class="form-select" required>
                <option value="available" {{ (old('status', $asset->status ?? '') === 'available') ? 'selected' : '' }}>Available</option>
                <option value="assigned" {{ (old('status', $asset->status ?? '') === 'assigned') ? 'selected' : '' }}>Assigned</option>
                <option value="under_repair" {{ (old('status', $asset->status ?? '') === 'under_repair') ? 'selected' : '' }}>Under Repair</option>
                <option value="disposed" {{ (old('status', $asset->status ?? '') === 'disposed') ? 'selected' : '' }}>Disposed</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Image</label>
            <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png">
        </div>
        <div class="col-12">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="2">{{ old('notes', $asset->notes ?? '') }}</textarea>
        </div>
    </div>
    <div class="text-end mt-3">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">{{ isset($asset) ? 'Update' : 'Create' }}</button>
    </div>
</form>
<script>
    App.form('#assetForm', {
        success: function () {
            $('#globalModal').modal('hide');
            if (window.assetsTable) window.assetsTable.draw();
        }
    });
</script>
