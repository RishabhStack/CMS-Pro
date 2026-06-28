@extends('layouts.master')

@section('title', 'Roles & Permissions')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Roles & Permissions</h5>
        <button type="button" class="btn btn-primary" onclick="openCreateModal()">
            <i class="bi bi-plus-lg"></i> Create Role
        </button>
    </div>
    <div class="card-body">
        <table id="dataTable-roles" class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Users</th>
                    <th>System</th>
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
    let roleTable;

    $(document).ready(function () {
        roleTable = $('#dataTable-roles').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("roles.list") }}',
            columns: [
                { data: 'name', name: 'name' },
                { data: 'description', name: 'description', defaultContent: '-' },
                { data: 'users_count', name: 'users_count', searchable: false },
                { data: 'is_system', name: 'is_system', render: function (data) {
                    return data
                        ? '<span class="badge bg-info">System</span>'
                        : '<span class="badge bg-secondary">Custom</span>';
                }},
                { data: 'status', name: 'status', render: function (data) {
                    return data === 'active'
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                }},
                { data: null, name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    return '<div class=\"btn-group btn-group-sm\">' +
                        '<button class=\"btn btn-info\" onclick=\"openEditModal(' + row.id + ')\" title=\"Edit\"><i class=\"bi bi-pencil\"></i></button>' +
                        '<button class=\"btn btn-warning\" onclick=\"managePermissions(' + row.id + ')\" title=\"Permissions\"><i class=\"bi bi-shield\"></i></button>' +
                        '<button class=\"btn btn-danger\" onclick=\"deleteRole(' + row.id + ')\" title=\"Delete\"><i class=\"bi bi-trash\"></i></button>' +
                        '</div>';
                } }
            ],
            responsive: true,
            order: [[0, 'desc']]
        });
        window.roleTable = roleTable;
    });

    function openCreateModal() {
        $.get('{{ route("roles.create") }}', function (html) {
            $('#globalModalLabel').text('Create Role');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function openEditModal(id) {
        $.get('{{ url("roles") }}/' + id + '/edit', function (html) {
            $('#globalModalLabel').text('Edit Role');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        }).fail(function (xhr) {
            const msg = xhr.responseJSON?.message || 'Failed to load edit form.';
            App.toast(msg, 'error');
        });
    }

    function managePermissions(id) {
        $.get('{{ url("roles") }}/' + id + '/permissions', function (html) {
            $('#globalModalLabel').text('Manage Permissions');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        }).fail(function (xhr) {
            const msg = xhr.responseJSON?.message || 'Failed to load permissions.';
            App.toast(msg, 'error');
        });
    }

    function deleteRole(id) {
        $('#confirmDeleteBtn').data('id', id);
        $('#deleteModal').modal('show');
    }

    $(document).on('click', '#confirmDeleteBtn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("roles") }}/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                $('#deleteModal').modal('hide');
                roleTable.draw();
                App.toast('Role deleted successfully', 'success');
            },
            error: function () {
                App.toast('Error deleting role', 'error');
            }
        });
    });
</script>
@endpush
