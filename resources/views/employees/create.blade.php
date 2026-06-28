<form action="{{ isset($employee) ? route('employees.update', $employee->id) : route('employees.store') }}" method="POST" id="employeeForm">
    @csrf
    @if(isset($employee))
        @method('PUT')
    @endif
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">First Name <span class="text-danger">*</span></label>
            <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $employee->user->first_name ?? '') }}" required>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Last Name <span class="text-danger">*</span></label>
            <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $employee->user->last_name ?? '') }}" required>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Employee Code</label>
            <input type="text" name="employee_code" class="form-control" value="{{ old('employee_code', $employee->employee_code ?? '') }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $employee->user->email ?? '') }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $employee->user->phone ?? '') }}">
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Department</label>
        <select name="department_id" class="form-select">
            <option value="">Select Department</option>
            @foreach($departments ?? [] as $dept)
                <option value="{{ $dept->id }}" {{ (old('department_id', $employee->department_id ?? '') == $dept->id) ? 'selected' : '' }}>{{ $dept->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Designation</label>
        <select name="designation_id" class="form-select">
            <option value="">Select Designation</option>
            @foreach($designations ?? [] as $desig)
                <option value="{{ $desig->id }}" {{ (old('designation_id', $employee->designation_id ?? '') == $desig->id) ? 'selected' : '' }}>{{ $desig->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Employment Type</label>
        <select name="employment_type" class="form-select">
            <option value="full_time" {{ (old('employment_type', $employee->employment_type ?? '') === 'full_time') ? 'selected' : '' }}>Full Time</option>
            <option value="part_time" {{ (old('employment_type', $employee->employment_type ?? '') === 'part_time') ? 'selected' : '' }}>Part Time</option>
            <option value="contract" {{ (old('employment_type', $employee->employment_type ?? '') === 'contract') ? 'selected' : '' }}>Contract</option>
            <option value="intern" {{ (old('employment_type', $employee->employment_type ?? '') === 'intern') ? 'selected' : '' }}>Intern</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Joining Date</label>
        <input type="date" name="joining_date" class="form-control" value="{{ old('joining_date', isset($employee) && $employee->joining_date ? $employee->joining_date->format('Y-m-d') : '') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="active" {{ (old('status', $employee->status ?? '') === 'active') ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ (old('status', $employee->status ?? '') === 'inactive') ? 'selected' : '' }}>Inactive</option>
            <option value="terminated" {{ (old('status', $employee->status ?? '') === 'terminated') ? 'selected' : '' }}>Terminated</option>
            <option value="resigned" {{ (old('status', $employee->status ?? '') === 'resigned') ? 'selected' : '' }}>Resigned</option>
        </select>
    </div>
    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">{{ isset($employee) ? 'Update' : 'Create' }}</button>
    </div>
</form>
<script>
    App.form('#employeeForm', {
        success: function () {
            $('#globalModal').modal('hide');
            if (window.employeeTable) window.employeeTable.draw();
        }
    });
</script>