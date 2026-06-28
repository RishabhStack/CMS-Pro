<div class="row g-3">
    <div class="col-md-6">
        <table class="table table-sm table-borderless mb-0">
            <tr>
                <td class="fw-semibold text-muted" style="width: 140px;">Name</td>
                <td>{{ $asset->name }}</td>
            </tr>
            <tr>
                <td class="fw-semibold text-muted">Type</td>
                <td><span class="badge bg-secondary">{{ ucfirst($asset->type) }}</span></td>
            </tr>
            <tr>
                <td class="fw-semibold text-muted">Serial Number</td>
                <td>{{ $asset->serial_number ?? '-' }}</td>
            </tr>
            <tr>
                <td class="fw-semibold text-muted">Brand</td>
                <td>{{ $asset->brand ?? '-' }}</td>
            </tr>
            <tr>
                <td class="fw-semibold text-muted">Model</td>
                <td>{{ $asset->model ?? '-' }}</td>
            </tr>
            <tr>
                <td class="fw-semibold text-muted">Status</td>
                <td>
                    @php $badges = ['available' => 'success', 'assigned' => 'primary', 'under_repair' => 'warning', 'disposed' => 'danger']; @endphp
                    <span class="badge bg-{{ $badges[$asset->status] ?? 'secondary' }}">{{ ucfirst(str_replace('_', ' ', $asset->status)) }}</span>
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
        <table class="table table-sm table-borderless mb-0">
            <tr>
                <td class="fw-semibold text-muted" style="width: 140px;">Purchase Date</td>
                <td>{{ $asset->purchase_date ? $asset->purchase_date->format('d/m/Y') : '-' }}</td>
            </tr>
            <tr>
                <td class="fw-semibold text-muted">Purchase Cost</td>
                <td>{{ $asset->purchase_cost ? currency($asset->purchase_cost) : '-' }}</td>
            </tr>
            <tr>
                <td class="fw-semibold text-muted">Warranty Expiry</td>
                <td>{{ $asset->warranty_expiry ? $asset->warranty_expiry->format('d/m/Y') : '-' }}</td>
            </tr>
            <tr>
                <td class="fw-semibold text-muted">Notes</td>
                <td>{{ $asset->notes ?? '-' }}</td>
            </tr>
        </table>
    </div>
    @if($asset->currentAssignment)
    <div class="col-12">
        <hr>
        <h6 class="fw-semibold">Current Assignment</h6>
        <table class="table table-sm table-borderless mb-0">
            <tr>
                <td class="fw-semibold text-muted" style="width: 140px;">Assigned To</td>
                <td>{{ $asset->currentAssignment->employee->full_name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="fw-semibold text-muted">Assigned At</td>
                <td>{{ $asset->currentAssignment->assigned_at ? $asset->currentAssignment->assigned_at->format('d/m/Y H:i') : '-' }}</td>
            </tr>
            @if($asset->currentAssignment->expected_return_date)
            <tr>
                <td class="fw-semibold text-muted">Expected Return</td>
                <td>{{ $asset->currentAssignment->expected_return_date->format('d/m/Y') }}</td>
            </tr>
            @endif
        </table>
    </div>
    @endif
</div>
<div class="text-end mt-3">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
