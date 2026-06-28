<form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" id="documentForm">
    @csrf
    <div class="mb-3">
        <label class="form-label">Employee <span class="text-danger">*</span></label>
        <select name="employee_id" class="form-select" required>
            <option value="">Select Employee</option>
            @foreach($employees as $employee)
                <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                    {{ $employee->user->first_name }} {{ $employee->user->last_name }} ({{ $employee->employee_code }})
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required maxlength="255">
    </div>
    <div class="mb-3">
        <label class="form-label">Type <span class="text-danger">*</span></label>
        <input type="text" name="type" class="form-control" value="{{ old('type') }}" required maxlength="100" placeholder="e.g. Contract, ID, Certificate">
    </div>
    <div class="mb-3">
        <label class="form-label">File <span class="text-danger">*</span></label>
        <input type="file" name="file" class="form-control" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
        <small class="text-muted">Accepted: PDF, DOC, DOCX, JPG, JPEG, PNG. Max 10 MB.</small>
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3" maxlength="1000">{{ old('description') }}</textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Expiry Date</label>
        <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date') }}">
    </div>
    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Upload</button>
    </div>
</form>
<script>
    App.form('#documentForm', {
        success: function () {
            $('#globalModal').modal('hide');
            if (window.documentTable) window.documentTable.draw();
        }
    });
</script>
