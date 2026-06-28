@extends('layouts.master')

@section('title', 'Shift Assignments')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0">Shift Assignments</h5>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <input type="date" id="filterDate" class="form-control" style="width: auto;" value="{{ date('Y-m-d') }}">
            <select id="filterEmployee" class="form-select" style="width: auto;">
                <option value="">All Employees</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                @endforeach
            </select>
            <select id="filterShift" class="form-select" style="width: auto;">
                <option value="">All Shifts</option>
                @foreach($shifts as $shift)
                    <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                @endforeach
            </select>
            @if(auth()->user()->hasRole(['Owner', 'Admin']))
                <button type="button" class="btn btn-success" onclick="openBulkAssignModal()">
                    <i class="bi bi-calendar-plus"></i> Bulk Assign
                </button>
                <button type="button" class="btn btn-primary" onclick="openAssignModal()">
                    <i class="bi bi-plus-lg"></i> Assign
                </button>
            @endif
        </div>
    </div>
    <div class="card-body">
        <table id="dataTable-assignments" class="table table-hover">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Shift</th>
                    <th>Date</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const isAdmin = @json(auth()->user()->hasRole(['Owner', 'Admin']));
    const employees = @json($employees);
    const shifts = @json($shifts);
    let assignmentTable;

    $(document).ready(function () {
        assignmentTable = $('#dataTable-assignments').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("shift-assignments.list") }}',
                data: function (d) {
                    d.date = $('#filterDate').val();
                    d.employee_id = $('#filterEmployee').val();
                    d.shift_id = $('#filterShift').val();
                }
            },
            columns: [
                { data: 'employee.full_name', name: 'employee_id', render: function (data, type, row) {
                    return row.employee ? row.employee.full_name : '-';
                }},
                { data: 'shift.name', name: 'shift_id', render: function (data, type, row) {
                    return row.shift ? row.shift.name : '-';
                }},
                { data: 'date', name: 'date', render: function (data) {
                    return data ? moment(data).format('DD/MM/YYYY') : '-';
                }},
                { data: 'notes', name: 'notes', render: function (data) {
                    return data || '-';
                }},
                { data: null, name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    let buttons = '<div class="btn-group btn-group-sm">';
                    if (isAdmin) {
                        buttons += '<button class="btn btn-primary" onclick="openEditAssignModal(' + row.id + ')" title="Edit"><i class="bi bi-pencil"></i></button>' +
                            '<button class="btn btn-danger" onclick="deleteAssignment(' + row.id + ')" title="Delete"><i class="bi bi-trash"></i></button>';
                    }
                    buttons += '</div>';
                    return buttons;
                }}
            ],
            responsive: true,
            order: [[2, 'desc']]
        });
        window.assignmentTable = assignmentTable;

        $('#filterDate, #filterEmployee, #filterShift').change(function () {
            assignmentTable.draw();
        });
    });

    function openAssignModal() {
        const html = `
            <form id="assignForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Employee <span class="text-danger">*</span></label>
                    <select name="employee_id" class="form-select" required>
                        <option value="">Select Employee</option>
                        ${employees.map(e => `<option value="${e.id}">${e.full_name}</option>`).join('')}
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Shift <span class="text-danger">*</span></label>
                    <select name="shift_id" class="form-select" required>
                        <option value="">Select Shift</option>
                        ${shifts.map(s => `<option value="${s.id}">${s.name}</option>`).join('')}
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Date <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="2"></textarea>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign</button>
                </div>
            </form>
        `;
        $('#globalModalLabel').text('Assign Shift');
        $('#globalModalBody').html(html);
        $('#globalModal').modal('show');

        App.form('#assignForm', {
            url: '{{ route("shift-assignments.store") }}',
            success: function () {
                $('#globalModal').modal('hide');
                assignmentTable.draw();
            }
        });
    }

    function openEditAssignModal(id) {
        $.get('{{ url("shift-assignments") }}/' + id, function (resp) {
            const a = resp.data;
            const html = `
                <form id="editAssignForm">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Shift <span class="text-danger">*</span></label>
                        <select name="shift_id" class="form-select" required>
                            <option value="">Select Shift</option>
                            ${shifts.map(s => `<option value="${s.id}" ${s.id === a.shift_id ? 'selected' : ''}>${s.name}</option>`).join('')}
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" class="form-control" value="${a.date}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2">${a.notes || ''}</textarea>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            `;
            $('#globalModalLabel').text('Edit Shift Assignment');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');

            App.form('#editAssignForm', {
                url: '{{ url("shift-assignments") }}/' + id,
                success: function () {
                    $('#globalModal').modal('hide');
                    assignmentTable.draw();
                }
            });
        });
    }

    function openBulkAssignModal() {
        const html = `
            <form id="bulkAssignForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Employees <span class="text-danger">*</span></label>
                    <select name="employee_ids[]" class="form-select" multiple required size="5">
                        ${employees.map(e => `<option value="${e.id}">${e.full_name}</option>`).join('')}
                    </select>
                    <small class="text-muted">Hold Ctrl/Cmd to select multiple</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Shift <span class="text-danger">*</span></label>
                    <select name="shift_id" class="form-select" required>
                        <option value="">Select Shift</option>
                        ${shifts.map(s => `<option value="${s.id}">${s.name}</option>`).join('')}
                    </select>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">End Date <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="2"></textarea>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Generate Roster</button>
                </div>
            </form>
        `;
        $('#globalModalLabel').text('Bulk Assign Shifts');
        $('#globalModalBody').html(html);
        $('#globalModal').modal('show');

        App.form('#bulkAssignForm', {
            url: '{{ route("shift-assignments.bulk-store") }}',
            success: function () {
                $('#globalModal').modal('hide');
                assignmentTable.draw();
            }
        });
    }

    function deleteAssignment(id) {
        $('#confirmDeleteBtn').data('id', id);
        $('#deleteModal').modal('show');
    }

    $(document).on('click', '#confirmDeleteBtn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("shift-assignments") }}/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                $('#deleteModal').modal('hide');
                assignmentTable.draw();
                App.toast('Assignment deleted successfully', 'success');
            },
            error: function () {
                App.toast('Error deleting assignment', 'error');
            }
        });
    });
</script>
@endpush
