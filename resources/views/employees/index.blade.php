@extends('layouts.master')

@section('title', 'Employees')

@section('content')
@php
    $currentUserEmployeeId = auth()->user()->employee?->id;
@endphp
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0">All Employees</h5>
        @if(auth()->user()->hasRole(['Owner', 'Admin']))
            <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                <i class="bi bi-plus-lg"></i> Add Employee
            </button>
        @endif
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <select id="filterDepartment" class="form-select">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select id="filterDesignation" class="form-select">
                    <option value="">All Designations</option>
                    @foreach($designations as $desig)
                        <option value="{{ $desig->id }}">{{ $desig->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select id="filterStatus" class="form-select">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="terminated">Terminated</option>
                    <option value="resigned">Resigned</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" id="tableSearch" class="form-control" placeholder="Search employees...">
            </div>
        </div>

        <table id="dataTable-employees" class="table table-hover">
            <thead>
                <tr>
                    <th>Employee Code</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Status</th>
                    <th>Joining Date</th>
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
    let employeeTable;

    $(document).ready(function () {
        employeeTable = $('#dataTable-employees').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("employees.list") }}',
                data: function (d) {
                    d.department_id = $('#filterDepartment').val();
                    d.designation_id = $('#filterDesignation').val();
                    d.status = $('#filterStatus').val();
                    d.search = $('#tableSearch').val();
                }
            },
            columns: [
                { data: 'employee_code', name: 'employee_code' },
                { data: 'full_name', name: 'full_name' },
                { data: 'email', name: 'email' },
                { data: 'department.name', name: 'department.name', defaultContent: '-' },
                { data: 'designation.name', name: 'designation.name', defaultContent: '-' },
                { data: 'status', name: 'status', render: function (data) {
                    const badges = { active: 'success', inactive: 'secondary', terminated: 'danger', resigned: 'warning' };
                    const label = data ? ucfirst(data) : '-';
                    return `<span class="badge bg-${badges[data] || 'secondary'}">${label}</span>`;
                }},
                { data: 'joining_date', name: 'joining_date', render: function (data) {
                    return data ? moment(data).format('DD/MM/YYYY') : '-';
                } },
                { data: null, name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    let buttons = '<div class=\"btn-group btn-group-sm\">' +
                        '<button class=\"btn btn-info\" onclick=\"viewEmployee(' + row.id + ')\" title=\"View\"><i class=\"bi bi-eye\"></i></button>';
                    if (isAdmin) {
                        buttons += '<button class=\"btn btn-primary\" onclick=\"openEditModal(' + row.id + ')\" title=\"Edit\"><i class=\"bi bi-pencil\"></i></button>';
                        if (row.id === {{ $currentUserEmployeeId ?? 'null' }}) {
                            buttons += '<button class=\"btn btn-secondary\" disabled title=\"Cannot delete yourself\"><i class=\"bi bi-lock\"></i></button>';
                        } else {
                            buttons += '<button class=\"btn btn-danger\" onclick=\"deleteEmployee(' + row.id + ')\" title=\"Delete\"><i class=\"bi bi-trash\"></i></button>';
                        }
                    }
                    buttons += '</div>';
                    return buttons;
                } }
            ],
            responsive: true,
            order: [[0, 'desc']]
        });
        window.employeeTable = employeeTable;

        $('#filterDepartment, #filterDesignation, #filterStatus').change(function () {
            employeeTable.draw();
        });

        $('#tableSearch').on('keyup', function () {
            employeeTable.search(this.value).draw();
        });
    });

    function openCreateModal() {
        $.get('{{ route("employees.create") }}', function (html) {
            $('#globalModalLabel').text('Add Employee');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function openEditModal(id) {
        $.get('{{ url("employees") }}/' + id + '/edit', function (html) {
            $('#globalModalLabel').text('Edit Employee');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function viewEmployee(id) {
        $.get('{{ url("employees") }}/' + id, function (html) {
            $('#globalModalLabel').text('View Employee');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function deleteEmployee(id) {
        $('#confirmDeleteBtn').data('id', id);
        $('#deleteModal').modal('show');
    }

    $(document).on('click', '#confirmDeleteBtn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("employees") }}/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                $('#deleteModal').modal('hide');
                employeeTable.draw();
                App.toast('Employee deleted successfully', 'success');
            },
            error: function () {
                App.toast('Error deleting employee', 'error');
            }
        });
    });

    function ucfirst(str) {
        if (!str) return '';
        str = String(str);
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
</script>
@endpush
