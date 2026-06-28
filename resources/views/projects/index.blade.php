@extends('layouts.master')

@section('title', 'Projects')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Projects</h5>
        @if(auth()->user()->hasRole(['Owner', 'Admin']))
            <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                <i class="bi bi-plus-lg"></i> Add Project
            </button>
        @endif
    </div>
    <div class="card-body">
        <table id="dataTable-projects" class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Description</th>
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
    let projectTable;

    $(document).ready(function () {
        projectTable = $('#dataTable-projects').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("projects.list") }}',
            columns: [
                { data: 'name', name: 'name' },
                { data: 'slug', name: 'slug' },
                { data: 'description', name: 'description', render: function (data) {
                    return data ? data.substring(0, 60) + (data.length > 60 ? '...' : '') : '-';
                }},
                { data: 'status', name: 'status', render: function (data) {
                    return data === 'active'
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                }},
                { data: null, name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    let buttons = '<div class="btn-group btn-group-sm">';
                    if (isAdmin) {
                        buttons += '<button class="btn btn-primary" onclick="openEditModal(' + row.id + ')" title="Edit"><i class="bi bi-pencil"></i></button>' +
                            '<button class="btn btn-danger" onclick="deleteProject(' + row.id + ')" title="Delete"><i class="bi bi-trash"></i></button>';
                    }
                    buttons += '</div>';
                    return buttons;
                }}
            ],
            responsive: true,
            order: [[0, 'asc']]
        });
        window.projectTable = projectTable;
    });

    function openCreateModal() {
        $.get('{{ route("projects.create") }}', function (html) {
            $('#globalModalLabel').text('Add Project');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function openEditModal(id) {
        $.get('{{ url("projects") }}/' + id + '/edit', function (html) {
            $('#globalModalLabel').text('Edit Project');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function deleteProject(id) {
        $('#confirmDeleteBtn').data('id', id);
        $('#deleteModal').modal('show');
    }

    $(document).on('click', '#confirmDeleteBtn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("projects") }}/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                $('#deleteModal').modal('hide');
                projectTable.draw();
                App.toast('Project deleted successfully', 'success');
            },
            error: function () {
                App.toast('Error deleting project', 'error');
            }
        });
    });
</script>
@endpush
