<form action="{{ isset($travelRequest) ? route('travel-requests.update', $travelRequest->id) : route('travel-requests.store') }}" method="POST" id="travelRequestForm">
    @csrf
    @if(isset($travelRequest))
        @method('PUT')
    @endif
    <div class="row g-3">
        @if(auth()->user()->hasRole(['Owner', 'Admin']) || !isset($travelRequest))
            <div class="col-md-6">
                <label class="form-label">Employee <span class="text-danger">*</span></label>
                <select name="employee_id" class="form-select" {{ isset($travelRequest) ? '' : (auth()->user()->hasRole(['Owner', 'Admin']) ? '' : 'disabled') }}>
                    <option value="">Select Employee</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ (old('employee_id', $travelRequest->employee_id ?? '') == $emp->id) ? 'selected' : '' }}>
                            {{ $emp->full_name }}
                        </option>
                    @endforeach
                </select>
                @if(!auth()->user()->hasRole(['Owner', 'Admin']) && !isset($travelRequest))
                    <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id ?? '' }}">
                @endif
            </div>
        @endif
        <div class="col-md-6">
            <label class="form-label">Destination <span class="text-danger">*</span></label>
            <input type="text" name="destination" class="form-control" value="{{ old('destination', $travelRequest->destination ?? '') }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">From Date <span class="text-danger">*</span></label>
            <input type="date" name="from_date" class="form-control flatpickr" value="{{ old('from_date', isset($travelRequest) && $travelRequest->from_date ? $travelRequest->from_date->format('Y-m-d') : '') }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">To Date <span class="text-danger">*</span></label>
            <input type="date" name="to_date" class="form-control flatpickr" value="{{ old('to_date', isset($travelRequest) && $travelRequest->to_date ? $travelRequest->to_date->format('Y-m-d') : '') }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Mode <span class="text-danger">*</span></label>
            <select name="mode" class="form-select" required>
                <option value="">Select Mode</option>
                @foreach(['flight', 'train', 'bus', 'cab', 'own'] as $mode)
                    <option value="{{ $mode }}" {{ (old('mode', $travelRequest->mode ?? '') === $mode) ? 'selected' : '' }}>{{ ucfirst($mode) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Estimated Cost</label>
            <input type="number" name="estimated_cost" class="form-control" step="0.01" min="0" value="{{ old('estimated_cost', $travelRequest->estimated_cost ?? '') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Actual Cost</label>
            <input type="number" name="actual_cost" class="form-control" step="0.01" min="0" value="{{ old('actual_cost', $travelRequest->actual_cost ?? '') }}">
        </div>
        <div class="col-12">
            <label class="form-label">Purpose <span class="text-danger">*</span></label>
            <textarea name="purpose" class="form-control" rows="3" required>{{ old('purpose', $travelRequest->purpose ?? '') }}</textarea>
        </div>
        <div class="col-12">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="2">{{ old('notes', $travelRequest->notes ?? '') }}</textarea>
        </div>
    </div>
    <div class="text-end mt-3">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">{{ isset($travelRequest) ? 'Update' : 'Create' }}</button>
    </div>
</form>
<script>
    App.form('#travelRequestForm', {
        success: function () {
            $('#globalModal').modal('hide');
            if (window.travelRequestsTable) window.travelRequestsTable.draw();
        }
    });
</script>
