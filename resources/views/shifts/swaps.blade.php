@extends('layouts.master')

@section('title', 'Shift Swap Requests')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0">Shift Swap Requests</h5>
        <div class="d-flex gap-2">
            <select id="filterStatus" class="form-select" style="width: auto;">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
            <button type="button" class="btn btn-primary" onclick="openCreateSwapModal()">
                <i class="bi bi-plus-lg"></i> New Swap Request
            </button>
        </div>
    </div>
    <div class="card-body">
        <table id="dataTable-swaps" class="table table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>From Employee</th>
                    <th>To Employee</th>
                    <th>Shift</th>
                    <th>Reason</th>
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
    let swapTable;

    $(document).ready(function () {
        swapTable = $('#dataTable-swaps').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("shift-swaps.list") }}',
                data: function (d) {
                    d.status = $('#filterStatus').val();
                }
            },
            columns: [
                { data: 'date', name: 'date', render: function (data) {
                    return data ? moment(data).format('DD/MM/YYYY') : '-';
                }},
                { data: 'from_employee.full_name', name: 'from_employee_id', render: function (data, type, row) {
                    return row.from_employee ? row.from_employee.full_name : '-';
                }},
                { data: 'to_employee.full_name', name: 'to_employee_id', render: function (data, type, row) {
                    return row.to_employee ? row.to_employee.full_name : '-';
                }},
                { data: 'shift_assignment.shift.name', name: 'shift_assignment_id', render: function (data, type, row) {
                    return row.shift_assignment && row.shift_assignment.shift ? row.shift_assignment.shift.name : '-';
                }},
                { data: 'reason', name: 'reason', render: function (data) {
                    return data ? data.substring(0, 40) + (data.length > 40 ? '...' : '') : '-';
                }},
                { data: 'status', name: 'status', render: function (data) {
                    const badges = {
                        pending: 'bg-warning text-dark',
                        approved: 'bg-success',
                        rejected: 'bg-danger'
                    };
                    const badge = badges[data] || 'bg-secondary';
                    return `<span class="badge ${badge}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                }},
                { data: null, name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    let buttons = '<div class="btn-group btn-group-sm">';
                    if (row.status === 'pending') {
                        if (isAdmin) {
                            buttons += '<button class="btn btn-success" onclick="approveSwap(' + row.id + ')" title="Approve"><i class="bi bi-check-lg"></i></button>' +
                                '<button class="btn btn-warning" onclick="rejectSwap(' + row.id + ')" title="Reject"><i class="bi bi-x-lg"></i></button>';
                        }
                        buttons += '<button class="btn btn-danger" onclick="deleteSwap(' + row.id + ')" title="Delete"><i class="bi bi-trash"></i></button>';
                    }
                    buttons += '</div>';
                    return buttons;
                }}
            ],
            responsive: true,
            order: [[0, 'desc']]
        });
        window.swapTable = swapTable;

        $('#filterStatus').change(function () {
            swapTable.draw();
        });
    });

    function openCreateSwapModal() {
        $.get('{{ route("shift-swaps.create") }}', function (html) {
            $('#globalModalLabel').text('New Shift Swap Request');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function approveSwap(id) {
        $.ajax({
            url: '{{ url("shift-swaps") }}/' + id + '/approve',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                swapTable.draw();
                App.toast('Swap request approved', 'success');
            },
            error: function () {
                App.toast('Error approving swap request', 'error');
            }
        });
    }

    function rejectSwap(id) {
        $.ajax({
            url: '{{ url("shift-swaps") }}/' + id + '/reject',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                swapTable.draw();
                App.toast('Swap request rejected', 'success');
            },
            error: function () {
                App.toast('Error rejecting swap request', 'error');
            }
        });
    }

    function deleteSwap(id) {
        $('#confirmDeleteBtn').data('id', id);
        $('#deleteModal').modal('show');
    }

    $(document).on('click', '#confirmDeleteBtn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("shift-swaps") }}/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                $('#deleteModal').modal('hide');
                swapTable.draw();
                App.toast('Swap request deleted successfully', 'success');
            },
            error: function () {
                App.toast('Error deleting swap request', 'error');
            }
        });
    });
</script>
@endpush
