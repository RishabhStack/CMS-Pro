<form action="{{ isset($attendance) ? route('attendance.update', $attendance->id) : route('attendance.store') }}" method="POST" id="attendanceForm">
    @csrf
    @if(isset($attendance))
        @method('PUT')
    @endif
    <div class="mb-3">
        <label class="form-label">Employee <span class="text-danger">*</span></label>
        <select name="employee_id" class="form-select" required>
            <option value="">Select Employee</option>
            @foreach($employees as $employee)
                <option value="{{ $employee->id }}" {{ (old('employee_id', $attendance->employee_id ?? '') == $employee->id) ? 'selected' : '' }}>
                    {{ $employee->full_name ?? $employee->user?->first_name . ' ' . $employee->user?->last_name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Date <span class="text-danger">*</span></label>
        <input type="date" name="date" class="form-control" value="{{ old('date', $attendance->date ?? date('Y-m-d')) }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Clock In <span class="text-danger">*</span></label>
        <input type="time" name="clock_in" class="form-control" value="{{ old('clock_in', $attendance->clock_in ?? '') }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Clock Out</label>
        <input type="time" name="clock_out" class="form-control" value="{{ old('clock_out', $attendance->clock_out ?? '') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="present" {{ (old('status', $attendance->status ?? '') === 'present') ? 'selected' : '' }}>Present</option>
            <option value="absent" {{ (old('status', $attendance->status ?? '') === 'absent') ? 'selected' : '' }}>Absent</option>
            <option value="late" {{ (old('status', $attendance->status ?? '') === 'late') ? 'selected' : '' }}>Late</option>
            <option value="half-day" {{ (old('status', $attendance->status ?? '') === 'half-day') ? 'selected' : '' }}>Half Day</option>
            <option value="leave" {{ (old('status', $attendance->status ?? '') === 'leave') ? 'selected' : '' }}>Leave</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Notes</label>
        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $attendance->notes ?? '') }}</textarea>
    </div>
    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">{{ isset($attendance) ? 'Update' : 'Save' }}</button>
    </div>
</form>
<script>
    App.form('#attendanceForm', {
        success: function () {
            $('#globalModal').modal('hide');
            if (window.attendanceTable) window.attendanceTable.draw();
        }
    });
</script>
