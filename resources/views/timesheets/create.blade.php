<form action="{{ isset($timesheet) ? route('timesheets.update', $timesheet->id) : route('timesheets.store') }}" method="POST" id="timesheetForm">
    @csrf
    @if(isset($timesheet))
        @method('PUT')
    @endif
    <div class="mb-3">
        <label class="form-label">Date <span class="text-danger">*</span></label>
        <input type="date" name="date" class="form-control" value="{{ old('date', isset($timesheet) && $timesheet->date ? $timesheet->date->format('Y-m-d') : date('Y-m-d')) }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Project</label>
        <select name="project_id" class="form-select">
            <option value="">Select Project</option>
            @foreach($projects as $project)
                <option value="{{ $project->id }}" {{ (old('project_id', $timesheet->project_id ?? '') == $project->id) ? 'selected' : '' }}>{{ $project->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Task Name</label>
        <input type="text" name="task_name" class="form-control" value="{{ old('task_name', $timesheet->task_name ?? '') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="2">{{ old('description', $timesheet->description ?? '') }}</textarea>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label">Start Time</label>
            <input type="datetime-local" name="start_time" class="form-control" value="{{ old('start_time', isset($timesheet) && $timesheet->start_time ? $timesheet->start_time->format('Y-m-d\TH:i') : '') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">End Time</label>
            <input type="datetime-local" name="end_time" class="form-control" value="{{ old('end_time', isset($timesheet) && $timesheet->end_time ? $timesheet->end_time->format('Y-m-d\TH:i') : '') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Total Hours <span class="text-danger">*</span></label>
            <input type="number" name="total_hours" class="form-control" value="{{ old('total_hours', $timesheet->total_hours ?? '') }}" step="0.25" min="0" max="24" required>
        </div>
    </div>
    <div class="mb-3">
        <div class="form-check">
            <input type="checkbox" name="is_billable" class="form-check-input" value="1" {{ old('is_billable', $timesheet->is_billable ?? false) ? 'checked' : '' }}>
            <label class="form-check-label">Billable</label>
        </div>
    </div>
    @if(!isset($timesheet))
        <div class="mb-3">
            <label class="form-label">Submit for Approval</label>
            <select name="status" class="form-select">
                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Save as Draft</option>
                <option value="submitted" {{ old('status') === 'submitted' ? 'selected' : '' }}>Submit for Approval</option>
            </select>
        </div>
    @endif
    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">{{ isset($timesheet) ? 'Update' : 'Create' }}</button>
    </div>
</form>
<script>
    App.form('#timesheetForm', {
        success: function () {
            $('#globalModal').modal('hide');
            if (window.timesheetTable) window.timesheetTable.draw();
        }
    });
</script>
