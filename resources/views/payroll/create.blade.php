<form action="{{ route('payroll.generate') }}" method="POST" id="payrollForm">
    @csrf
    <div class="mb-3">
        <label class="form-label">Employee <span class="text-danger">*</span></label>
        <select name="employee_ids[]" class="form-select" multiple required>
            @foreach($employees as $employee)
                <option value="{{ $employee->id }}" {{ in_array($employee->id, old('employee_ids', [])) ? 'selected' : '' }}>
                    {{ $employee->user->first_name }} {{ $employee->user->last_name }} ({{ $employee->employee_code }})
                </option>
            @endforeach
        </select>
        <div class="form-text">Select one or more employees for payroll generation.</div>
    </div>
    <div class="mb-3">
        <label class="form-label">Month <span class="text-danger">*</span></label>
        <select name="month" class="form-select" required>
            <option value="">Select Month</option>
            @foreach($months as $m)
                <option value="{{ $m }}" {{ old('month') == $m ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Year <span class="text-danger">*</span></label>
        <select name="year" class="form-select" required>
            <option value="">Select Year</option>
            @foreach($years as $y)
                <option value="{{ $y }}" {{ old('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
    </div>
    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Generate Payroll</button>
    </div>
</form>
<script>
    App.form('#payrollForm', {
        success: function () {
            $('#globalModal').modal('hide');
            if (window.payrollTable) window.payrollTable.draw();
        }
    });
</script>
