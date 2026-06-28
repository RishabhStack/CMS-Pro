@extends('layouts.master')

@section('title', 'Shift Scheduling')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Shifts</h5>
        @if(auth()->user()->hasRole(['Owner', 'Admin']))
            <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                <i class="bi bi-plus-lg"></i> Add Shift
            </button>
        @endif
    </div>
    <div class="card-body">
        <table id="dataTable-shifts" class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Grace (min)</th>
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
    let shiftTable;

    $(document).ready(function () {
        shiftTable = $('#dataTable-shifts').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("shifts.list") }}',
            columns: [
                { data: 'name', name: 'name' },
                { data: 'start_time', name: 'start_time', render: function (data) {
                    return data ? moment(data, 'HH:mm').format('hh:mm A') : '-';
                }},
                { data: 'end_time', name: 'end_time', render: function (data) {
                    return data ? moment(data, 'HH:mm').format('hh:mm A') : '-';
                }},
                { data: 'grace_minutes', name: 'grace_minutes', searchable: false },
                { data: 'status', name: 'status', render: function (data) {
                    return data === 'active'
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                }},
                { data: null, name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    let buttons = '<div class="btn-group btn-group-sm">';
                    if (isAdmin) {
                        buttons += '<button class="btn btn-primary" onclick="openEditModal(' + row.id + ')" title="Edit"><i class="bi bi-pencil"></i></button>' +
                            '<button class="btn btn-danger" onclick="deleteShift(' + row.id + ')" title="Delete"><i class="bi bi-trash"></i></button>';
                    }
                    buttons += '</div>';
                    return buttons;
                }}
            ],
            responsive: true,
            order: [[0, 'asc']]
        });
        window.shiftTable = shiftTable;
    });

    function openCreateModal() {
        $.get('{{ route("shifts.create") }}', function (html) {
            $('#globalModalLabel').text('Add Shift');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function openEditModal(id) {
        $.get('{{ url("shifts") }}/' + id + '/edit', function (html) {
            $('#globalModalLabel').text('Edit Shift');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function deleteShift(id) {
        $('#confirmDeleteBtn').data('id', id);
        $('#deleteModal').modal('show');
    }

    $(document).on('click', '#confirmDeleteBtn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("shifts") }}/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                $('#deleteModal').modal('hide');
                shiftTable.draw();
                App.toast('Shift deleted successfully', 'success');
            },
            error: function () {
                App.toast('Error deleting shift', 'error');
            }
        });
    });
</script>
@endpush
