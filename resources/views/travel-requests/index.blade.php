@extends('layouts.master')

@section('title', 'Travel Requests')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0">Travel Requests</h5>
        @if(auth()->user()->hasRole(['Owner', 'Admin']) || auth()->user()->employee)
            <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                <i class="bi bi-plus-lg"></i> New Travel Request
            </button>
        @endif
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <select id="filterStatus" class="form-select">
                    <option value="">All Status</option>
                    <option value="draft">Draft</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="settled">Settled</option>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filterEmployee" class="form-select">
                    <option value="">All Employees</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select id="filterMode" class="form-select">
                    <option value="">All Modes</option>
                    @foreach($travelModes as $mode)
                        <option value="{{ $mode }}">{{ ucfirst($mode) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <table id="dataTable-travel-requests" class="table table-hover">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Destination</th>
                    <th>Dates</th>
                    <th>Mode</th>
                    <th>Est. Cost</th>
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
    const currentEmployeeId = {{ auth()->user()->employee?->id ?? 'null' }};
    let travelRequestsTable;

    $(document).ready(function () {
        $('.flatpickr').flatpickr({ dateFormat: 'Y-m-d', allowInput: true });

        travelRequestsTable = $('#dataTable-travel-requests').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("travel-requests.list") }}',
                data: function (d) {
                    d.status = $('#filterStatus').val();
                    d.employee_id = $('#filterEmployee').val();
                    d.mode = $('#filterMode').val();
                }
            },
            columns: [
                { data: 'employee.full_name', name: 'employee.full_name' },
                { data: 'destination', name: 'destination' },
                { data: null, name: 'from_date', render: function (data) {
                    return moment(data.from_date).format('DD/MM/YYYY') + ' - ' + moment(data.to_date).format('DD/MM/YYYY');
                }},
                { data: 'mode', name: 'mode', render: function (data) {
                    const icons = { flight: 'airplane', train: 'train-front', bus: 'bus-front', cab: 'car-front', own: 'person' };
                    return `<i class="bi bi-${icons[data] || 'question'}"></i> ${ucfirst(data)}`;
                }},
                { data: 'estimated_cost', name: 'estimated_cost', render: function (data) {
                    return data ? '{{ setting("currency_symbol", "$") }}' + Number(data).toFixed(2) : '-';
                }},
                { data: 'status', name: 'status', render: function (data) {
                    const badges = { draft: 'secondary', pending: 'warning', approved: 'success', rejected: 'danger', cancelled: 'secondary', settled: 'info' };
                    return `<span class="badge bg-${badges[data] || 'secondary'}">${ucfirst(data)}</span>`;
                }},
                { data: null, name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    let buttons = '<div class="btn-group btn-group-sm">' +
                        '<button class="btn btn-info" onclick="viewTravelRequest(' + row.id + ')" title="View"><i class="bi bi-eye"></i></button>';

                    if (isAdmin) {
                        buttons += '<button class="btn btn-primary" onclick="openEditModal(' + row.id + ')" title="Edit"><i class="bi bi-pencil"></i></button>' +
                            '<button class="btn btn-danger" onclick="deleteTravelRequest(' + row.id + ')" title="Delete"><i class="bi bi-trash"></i></button>';
                    }

                    if (row.status === 'draft' && (isAdmin || row.employee_id === currentEmployeeId)) {
                        buttons += '<button class="btn btn-success" onclick="submitTravelRequest(' + row.id + ')" title="Submit for Approval"><i class="bi bi-send"></i></button>';
                    }

                    if (isAdmin && (row.status === 'draft' || row.status === 'pending')) {
                        buttons += '<button class="btn btn-success" onclick="approveTravelRequest(' + row.id + ')" title="Approve"><i class="bi bi-check-lg"></i></button>' +
                            '<button class="btn btn-warning" onclick="rejectTravelRequest(' + row.id + ')" title="Reject"><i class="bi bi-x-lg"></i></button>';
                    }

                    buttons += '</div>';
                    return buttons;
                }}
            ],
            responsive: true,
            order: [[2, 'desc']]
        });
        window.travelRequestsTable = travelRequestsTable;

        $('#filterStatus, #filterEmployee, #filterMode').change(function () {
            travelRequestsTable.draw();
        });
    });

    function openCreateModal() {
        $.get('{{ route("travel-requests.create") }}', function (html) {
            $('#globalModalLabel').text('New Travel Request');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function openEditModal(id) {
        $.get('{{ url("travel-requests") }}/' + id + '/edit', function (html) {
            $('#globalModalLabel').text('Edit Travel Request');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function viewTravelRequest(id) {
        $.get('{{ url("travel-requests") }}/' + id, function (html) {
            $('#globalModalLabel').text('View Travel Request');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function submitTravelRequest(id) {
        Swal.fire({
            title: 'Submit for Approval?',
            text: 'Are you sure you want to submit this travel request for approval?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("travel-requests") }}/' + id + '/submit',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function () {
                        travelRequestsTable.draw();
                        App.toast('Travel request submitted successfully', 'success');
                    },
                    error: function (xhr) {
                        App.toast(xhr.responseJSON?.message || 'Error submitting request', 'error');
                    }
                });
            }
        });
    }

    function approveTravelRequest(id) {
        Swal.fire({
            title: 'Approve Request?',
            text: 'Are you sure you want to approve this travel request?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, approve',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("travel-requests") }}/' + id + '/approve',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function () {
                        travelRequestsTable.draw();
                        App.toast('Travel request approved', 'success');
                    },
                    error: function (xhr) {
                        App.toast(xhr.responseJSON?.message || 'Error approving request', 'error');
                    }
                });
            }
        });
    }

    function rejectTravelRequest(id) {
        Swal.fire({
            title: 'Reject Request?',
            text: 'Are you sure you want to reject this travel request?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, reject',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("travel-requests") }}/' + id + '/reject',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function () {
                        travelRequestsTable.draw();
                        App.toast('Travel request rejected', 'error');
                    },
                    error: function (xhr) {
                        App.toast(xhr.responseJSON?.message || 'Error rejecting request', 'error');
                    }
                });
            }
        });
    }

    function deleteTravelRequest(id) {
        $('#confirmDeleteBtn').data('id', id);
        $('#deleteModal').modal('show');
    }

    $(document).on('click', '#confirmDeleteBtn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("travel-requests") }}/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                $('#deleteModal').modal('hide');
                travelRequestsTable.draw();
                App.toast('Travel request deleted successfully', 'success');
            },
            error: function () {
                App.toast('Error deleting travel request', 'error');
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
