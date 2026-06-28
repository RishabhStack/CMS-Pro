@extends('layouts.master')

@section('title', 'Designations')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Designations</h5>
        @if(auth()->user()->hasRole(['Owner', 'Admin']))
            <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                <i class="bi bi-plus-lg"></i> Add Designation
            </button>
        @endif
    </div>
    <div class="card-body">
        <table id="dataTable-designations" class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Description</th>
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
    let designationTable;

    $(document).ready(function () {
        designationTable = $('#dataTable-designations').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("designations.list") }}',
            columns: [
                { data: 'name', name: 'name' },
                { data: 'department.name', name: 'department.name', defaultContent: '-' },
                { data: 'description', name: 'description', defaultContent: '-' },
                { data: 'employees_count', name: 'employees_count', searchable: false },
                { data: 'status', name: 'status', render: function (data) {
                    return data === 'active'
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                }},
                { data: null, name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    let buttons = '<div class=\"btn-group btn-group-sm\">' +
                        '<button class=\"btn btn-info\" onclick=\"viewDesignation(' + row.id + ')\" title=\"View\"><i class=\"bi bi-eye\"></i></button>';
                    if (isAdmin) {
                        buttons += '<button class=\"btn btn-primary\" onclick=\"openEditModal(' + row.id + ')\" title=\"Edit\"><i class=\"bi bi-pencil\"></i></button>' +
                            '<button class=\"btn btn-danger\" onclick=\"deleteDesignation(' + row.id + ')\" title=\"Delete\"><i class=\"bi bi-trash\"></i></button>';
                    }
                    buttons += '</div>';
                    return buttons;
                } }
            ],
            responsive: true,
            order: [[0, 'desc']]
        });
        window.designationTable = designationTable;
    });

    function openCreateModal() {
        $.get('{{ route("designations.create") }}', function (html) {
            $('#globalModalLabel').text('Add Designation');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function openEditModal(id) {
        $.get('{{ url("designations") }}/' + id + '/edit', function (html) {
            $('#globalModalLabel').text('Edit Designation');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function viewDesignation(id) {
        $.get('{{ url("designations") }}/' + id, function (html) {
            $('#globalModalLabel').text('View Designation');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function deleteDesignation(id) {
        $('#confirmDeleteBtn').data('id', id);
        $('#deleteModal').modal('show');
    }

    $(document).on('click', '#confirmDeleteBtn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("designations") }}/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                $('#deleteModal').modal('hide');
                designationTable.draw();
                App.toast('Designation deleted successfully', 'success');
            },
            error: function () {
                App.toast('Error deleting designation', 'error');
            }
        });
    });
</script>
@endpush
