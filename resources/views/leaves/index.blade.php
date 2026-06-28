@extends('layouts.master')

@section('title', 'Leaves')

@section('content')
<div class="row g-3 mb-4">
    @foreach($leaveBalances as $balance)
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card text-center border-{{ $balance->color ?? 'primary' }}">
                <div class="card-body">
                    <h6 class="card-title text-muted mb-1">{{ $balance->name }}</h6>
                    <h3 class="mb-0 text-{{ $balance->color ?? 'primary' }}">{{ $balance->used }}/{{ $balance->total }}</h3>
                    <small class="text-muted">Remaining: {{ $balance->total - $balance->used }}</small>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0">Leave Applications</h5>
        <button type="button" class="btn btn-primary" onclick="openApplyModal()">
            <i class="bi bi-plus-lg"></i> Apply Leave
        </button>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <select id="filterStatus" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" id="filterDateFrom" class="form-control flatpickr" placeholder="From date">
            </div>
            <div class="col-md-3">
                <input type="text" id="filterDateTo" class="form-control flatpickr" placeholder="To date">
            </div>
            <div class="col-md-3">
                <select id="filterLeaveType" class="form-select">
                    <option value="">All Leave Types</option>
                    @foreach($leaveTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <table id="dataTable-leaves" class="table table-hover">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Leave Type</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Days</th>
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
    let leavesTable;

    $(document).ready(function () {
        flatpickr('.flatpickr', {
            dateFormat: 'Y-m-d',
            allowInput: true
        });

        leavesTable = $('#dataTable-leaves').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("leaves.list") }}',
                data: function (d) {
                    d.status = $('#filterStatus').val();
                    d.from_date = $('#filterDateFrom').val();
                    d.to_date = $('#filterDateTo').val();
                    d.leave_type_id = $('#filterLeaveType').val();
                }
            },
            columns: [
                { data: 'employee.full_name', name: 'employee.full_name' },
                { data: 'leave_type.name', name: 'leave_type.name' },
                { data: 'from_date', name: 'start_date', render: function (data) {
                    return data ? moment(data).format('DD/MM/YYYY') : '-';
                }},
                { data: 'to_date', name: 'end_date', render: function (data) {
                    return data ? moment(data).format('DD/MM/YYYY') : '-';
                }},
                { data: 'total_days', name: 'total_days', searchable: false },
                { data: 'status', name: 'status', render: function (data) {
                    const badges = { pending: 'warning', approved: 'success', rejected: 'danger', cancelled: 'secondary' };
                    return `<span class="badge bg-${badges[data] || 'secondary'}">${ucfirst(data)}</span>`;
                }},
                { data: null, name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    let buttons = '<div class=\"btn-group btn-group-sm\">' +
                        '<button class=\"btn btn-info\" onclick=\"viewLeave(' + row.id + ')\" title=\"View\"><i class=\"bi bi-eye\"></i></button>';
                    if (isAdmin) {
                        buttons += '<button class=\"btn btn-success\" onclick=\"approveLeave(' + row.id + ')\" title=\"Approve\"><i class=\"bi bi-check-lg\"></i></button>' +
                            '<button class=\"btn btn-warning\" onclick=\"rejectLeave(' + row.id + ')\" title=\"Reject\"><i class=\"bi bi-x-lg\"></i></button>' +
                            '<button class=\"btn btn-danger\" onclick=\"deleteLeave(' + row.id + ')\" title=\"Delete\"><i class=\"bi bi-trash\"></i></button>';
                    }
                    buttons += '</div>';
                    return buttons;
                } }
            ],
            responsive: true,
            order: [[2, 'desc']]
        });
        window.leavesTable = leavesTable;

        $('#filterStatus, #filterDateFrom, #filterDateTo, #filterLeaveType').change(function () {
            leavesTable.draw();
        });
    });

    function openApplyModal() {
        $.get('{{ route("leaves.create") }}', function (html) {
            $('#globalModalLabel').text('Apply Leave');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function viewLeave(id) {
        $.get('{{ url("leaves") }}/' + id, function (html) {
            $('#globalModalLabel').text('View Leave');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function approveLeave(id) {
        Swal.fire({
            title: 'Approve Leave?',
            text: 'Are you sure you want to approve this leave request?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, approve',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("leaves") }}/' + id + '/approve',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function () {
                        leavesTable.draw();
                        App.toast('Leave approved successfully', 'success');
                    },
                    error: function () {
                        App.toast('Error approving leave', 'error');
                    }
                });
            }
        });
    }

    function rejectLeave(id) {
        Swal.fire({
            title: 'Reject Leave?',
            text: 'Are you sure you want to reject this leave request?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, reject',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("leaves") }}/' + id + '/reject',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function () {
                        leavesTable.draw();
                        App.toast('Leave rejected', 'error');
                    },
                    error: function () {
                        App.toast('Error rejecting leave', 'error');
                    }
                });
            }
        });
    }

    function deleteLeave(id) {
        $('#confirmDeleteBtn').data('id', id);
        $('#deleteModal').modal('show');
    }

    $(document).on('click', '#confirmDeleteBtn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("leaves") }}/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                $('#deleteModal').modal('hide');
                leavesTable.draw();
                App.toast('Leave deleted successfully', 'success');
            },
            error: function () {
                App.toast('Error deleting leave', 'error');
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
