@extends('layouts.master')

@section('title', 'Leave Types')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Leave Types</h5>
        @if(auth()->user()->hasRole(['Owner', 'Admin']))
            <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                <i class="bi bi-plus-lg"></i> Add Leave Type
            </button>
        @endif
    </div>
    <div class="card-body">
        <table id="dataTable-leave-types" class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Days / Year</th>
                    <th>Carry Forward</th>
                    <th>Color</th>
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
    let leaveTypeTable;

    $(document).ready(function () {
        leaveTypeTable = $('#dataTable-leave-types').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("leave-types.list") }}',
            columns: [
                { data: 'name', name: 'name' },
                { data: 'days_per_year', name: 'days_per_year', searchable: false },
                { data: 'carry_forward', name: 'carry_forward', render: function (data) {
                    return data
                        ? '<span class="badge bg-success">Yes</span>'
                        : '<span class="badge bg-secondary">No</span>';
                }},
                { data: 'color', name: 'color', render: function (data) {
                    return data ? `<span class="badge" style="background-color: ${data}">${data}</span>` : '-';
                }},
                { data: 'status', name: 'status', render: function (data) {
                    return data === 'active'
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                }},
                { data: null, name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    let buttons = '<div class=\"btn-group btn-group-sm\">';
                    if (isAdmin) {
                        buttons += '<button class=\"btn btn-primary\" onclick=\"openEditModal(' + row.id + ')\" title=\"Edit\"><i class=\"bi bi-pencil\"></i></button>' +
                            '<button class=\"btn btn-danger\" onclick=\"deleteLeaveType(' + row.id + ')\" title=\"Delete\"><i class=\"bi bi-trash\"></i></button>';
                    }
                    buttons += '</div>';
                    return buttons;
                } }
            ],
            responsive: true,
            order: [[0, 'desc']]
        });
        window.leaveTypeTable = leaveTypeTable;
    });

    function openCreateModal() {
        $.get('{{ route("leave-types.create") }}', function (html) {
            $('#globalModalLabel').text('Add Leave Type');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function openEditModal(id) {
        $.get('{{ url("leave-types") }}/' + id + '/edit', function (html) {
            $('#globalModalLabel').text('Edit Leave Type');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function deleteLeaveType(id) {
        $('#confirmDeleteBtn').data('id', id);
        $('#deleteModal').modal('show');
    }

    $(document).on('click', '#confirmDeleteBtn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("leave-types") }}/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                $('#deleteModal').modal('hide');
                leaveTypeTable.draw();
                App.toast('Leave type deleted successfully', 'success');
            },
            error: function () {
                App.toast('Error deleting leave type', 'error');
            }
        });
    });
</script>
@endpush
