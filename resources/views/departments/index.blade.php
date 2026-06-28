@extends('layouts.master')

@section('title', 'Departments')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Departments</h5>
        @if(auth()->user()->hasRole(['Owner', 'Admin']))
            <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                <i class="bi bi-plus-lg"></i> Add Department
            </button>
        @endif
    </div>
    <div class="card-body">
        <table id="dataTable-departments" class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Manager</th>
                    <th>Employees</th>
                    <th>Status</th>
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
    let departmentTable;

    $(document).ready(function () {
        departmentTable = $('#dataTable-departments').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("departments.list") }}',
            columns: [
                { data: 'name', name: 'name' },
                { data: 'description', name: 'description', defaultContent: '-' },
                { data: 'manager.full_name', name: 'manager.full_name', defaultContent: '-' },
                { data: 'employees_count', name: 'employees_count', searchable: false },
                { data: 'status', name: 'status', render: function (data) {
                    return data === 'active'
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                }},
                { data: null, name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    let buttons = '<div class=\"btn-group btn-group-sm\">' +
                        '<button class=\"btn btn-info\" onclick=\"viewDepartment(' + row.id + ')\" title=\"View\"><i class=\"bi bi-eye\"></i></button>';
                    if (isAdmin) {
                        buttons += '<button class=\"btn btn-primary\" onclick=\"openEditModal(' + row.id + ')\" title=\"Edit\"><i class=\"bi bi-pencil\"></i></button>' +
                            '<button class=\"btn btn-danger\" onclick=\"deleteDepartment(' + row.id + ')\" title=\"Delete\"><i class=\"bi bi-trash\"></i></button>';
                    }
                    buttons += '</div>';
                    return buttons;
                } }
            ],
            responsive: true,
            order: [[0, 'desc']]
        });
        window.departmentTable = departmentTable;
    });

    function openCreateModal() {
        $.get('{{ route("departments.create") }}', function (html) {
            $('#globalModalLabel').text('Add Department');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function openEditModal(id) {
        $.get('{{ url("departments") }}/' + id + '/edit', function (html) {
            $('#globalModalLabel').text('Edit Department');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function viewDepartment(id) {
        $.get('{{ url("departments") }}/' + id, function (html) {
            $('#globalModalLabel').text('View Department');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function deleteDepartment(id) {
        $('#confirmDeleteBtn').data('id', id);
        $('#deleteModal').modal('show');
    }

    $(document).on('click', '#confirmDeleteBtn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("departments") }}/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                $('#deleteModal').modal('hide');
                departmentTable.draw();
                App.toast('Department deleted successfully', 'success');
            },
            error: function () {
                App.toast('Error deleting department', 'error');
            }
        });
    });
</script>
@endpush
