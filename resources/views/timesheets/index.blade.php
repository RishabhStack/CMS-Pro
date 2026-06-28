@extends('layouts.master')

@section('title', 'Timesheets')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0">Timesheets</h5>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <input type="date" id="filterDateFrom" class="form-control" style="width: auto;" placeholder="From Date">
            <input type="date" id="filterDateTo" class="form-control" style="width: auto;" placeholder="To Date">
            <select id="filterProject" class="form-select" style="width: auto;">
                <option value="">All Projects</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
            </select>
            <select id="filterStatus" class="form-select" style="width: auto;">
                <option value="">All Status</option>
                <option value="draft">Draft</option>
                <option value="submitted">Submitted</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
            <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                <i class="bi bi-plus-lg"></i> Add Timesheet
            </button>
        </div>
    </div>
    <div class="card-body">
        <table id="dataTable-timesheets" class="table table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Employee</th>
                    <th>Project</th>
                    <th>Task</th>
                    <th>Hours</th>
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
    const projects = @json($projects);
    let timesheetTable;

    $(document).ready(function () {
        timesheetTable = $('#dataTable-timesheets').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("timesheets.list") }}',
                data: function (d) {
                    d.date_from = $('#filterDateFrom').val();
                    d.date_to = $('#filterDateTo').val();
                    d.project_id = $('#filterProject').val();
                    d.status = $('#filterStatus').val();
                }
            },
            columns: [
                { data: 'date', name: 'date', render: function (data) {
                    return data ? moment(data).format('DD/MM/YYYY') : '-';
                }},
                { data: 'employee.full_name', name: 'employee_id', render: function (data, type, row) {
                    return row.employee ? row.employee.full_name : '-';
                }},
                { data: 'project.name', name: 'project_id', render: function (data, type, row) {
                    return row.project ? row.project.name : '-';
                }},
                { data: 'task_name', name: 'task_name', render: function (data) {
                    return data || '-';
                }},
                { data: 'total_hours', name: 'total_hours', searchable: false, render: function (data) {
                    return data ? parseFloat(data).toFixed(2) : '0.00';
                }},
                { data: 'status', name: 'status', render: function (data) {
                    const badges = {
                        draft: 'bg-secondary',
                        submitted: 'bg-info',
                        approved: 'bg-success',
                        rejected: 'bg-danger'
                    };
                    const badge = badges[data] || 'bg-secondary';
                    return `<span class="badge ${badge}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                }},
                { data: null, name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    let buttons = '<div class="btn-group btn-group-sm">';
                    if (row.status === 'draft') {
                        buttons += '<button class="btn btn-primary" onclick="openEditModal(' + row.id + ')" title="Edit"><i class="bi bi-pencil"></i></button>';
                    }
                    if (isAdmin && row.status === 'submitted') {
                        buttons += '<button class="btn btn-success" onclick="approveTimesheet(' + row.id + ')" title="Approve"><i class="bi bi-check-lg"></i></button>' +
                            '<button class="btn btn-warning" onclick="rejectTimesheet(' + row.id + ')" title="Reject"><i class="bi bi-x-lg"></i></button>';
                    }
                    if (isAdmin) {
                        buttons += '<button class="btn btn-danger" onclick="deleteTimesheet(' + row.id + ')" title="Delete"><i class="bi bi-trash"></i></button>';
                    }
                    buttons += '</div>';
                    return buttons;
                }}
            ],
            responsive: true,
            order: [[0, 'desc']]
        });
        window.timesheetTable = timesheetTable;

        $('#filterDateFrom, #filterDateTo, #filterProject, #filterStatus').change(function () {
            timesheetTable.draw();
        });
    });

    function openCreateModal() {
        $.get('{{ route("timesheets.create") }}', function (html) {
            $('#globalModalLabel').text('Add Timesheet');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function openEditModal(id) {
        $.get('{{ url("timesheets") }}/' + id + '/edit', function (html) {
            $('#globalModalLabel').text('Edit Timesheet');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function deleteTimesheet(id) {
        $('#confirmDeleteBtn').data('id', id);
        $('#deleteModal').modal('show');
    }

    $(document).on('click', '#confirmDeleteBtn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("timesheets") }}/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                $('#deleteModal').modal('hide');
                timesheetTable.draw();
                App.toast('Timesheet deleted successfully', 'success');
            },
            error: function () {
                App.toast('Error deleting timesheet', 'error');
            }
        });
    });

    function approveTimesheet(id) {
        $.ajax({
            url: '{{ url("timesheets") }}/' + id + '/approve',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                timesheetTable.draw();
                App.toast('Timesheet approved successfully', 'success');
            },
            error: function () {
                App.toast('Error approving timesheet', 'error');
            }
        });
    }

    function rejectTimesheet(id) {
        Swal.fire({
            title: 'Reject Timesheet',
            input: 'textarea',
            inputLabel: 'Reason for rejection',
            inputPlaceholder: 'Enter rejection reason...',
            inputAttributes: { required: true },
            showCancelButton: true,
            confirmButtonText: 'Reject',
            confirmButtonColor: '#dc3545',
            cancelButtonText: 'Cancel',
            inputValidator: (value) => {
                if (!value) return 'Please enter a reason';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("timesheets") }}/' + id + '/reject',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        rejection_reason: result.value
                    },
                    success: function () {
                        timesheetTable.draw();
                        App.toast('Timesheet rejected', 'success');
                    },
                    error: function () {
                        App.toast('Error rejecting timesheet', 'error');
                    }
                });
            }
        });
    }
</script>
@endpush
