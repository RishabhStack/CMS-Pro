<form action="{{ route('leaves.store') }}" method="POST" id="leaveForm">
    @csrf
    <div class="mb-3">
        <label class="form-label">Leave Type <span class="text-danger">*</span></label>
        <select name="leave_type_id" class="form-select" required>
            <option value="">Select Leave Type</option>
            @foreach($leaveTypes as $type)
                <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Start Date <span class="text-danger">*</span></label>
        <input type="date" name="start_date" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">End Date <span class="text-danger">*</span></label>
        <input type="date" name="end_date" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Reason <span class="text-danger">*</span></label>
        <textarea name="reason" class="form-control" rows="4" required></textarea>
    </div>
    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
<script>
    App.form('#leaveForm', {
        success: function () {
            $('#globalModal').modal('hide');
            if (window.leavesTable) window.leavesTable.draw();
        }
    });
</script>
