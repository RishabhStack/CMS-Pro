<form action="{{ route('shift-swaps.store') }}" method="POST" id="swapForm">
    @csrf
    <div class="mb-3">
        <label class="form-label">My Shift Assignment <span class="text-danger">*</span></label>
        <select name="shift_assignment_id" class="form-select" required>
            <option value="">Select Assignment</option>
            @foreach($myAssignments as $assignment)
                <option value="{{ $assignment->id }}">
                    {{ $assignment->shift->name ?? 'Shift' }} - {{ $assignment->date->format('d/m/Y') }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Swap With Employee <span class="text-danger">*</span></label>
        <select name="to_employee_id" class="form-select" required>
            <option value="">Select Employee</option>
            @foreach($employees as $emp)
                <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Date <span class="text-danger">*</span></label>
        <input type="date" name="date" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Reason <span class="text-danger">*</span></label>
        <textarea name="reason" class="form-control" rows="3" required></textarea>
    </div>
    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Submit Request</button>
    </div>
</form>
<script>
    App.form('#swapForm', {
        success: function () {
            $('#globalModal').modal('hide');
            if (window.swapTable) window.swapTable.draw();
        }
    });
</script>
