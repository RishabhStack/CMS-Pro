@extends('layouts.master')

@section('title', 'Exit Management')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0">Resignations</h5>
        <button type="button" class="btn btn-primary" onclick="openSubmitModal()">
            <i class="bi bi-plus-lg"></i> Submit Resignation
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
                    <option value="cleared">Cleared</option>
                </select>
            </div>
        </div>

        <table id="dataTable-resignations" class="table table-hover">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Notice Date</th>
                    <th>Last Working Day</th>
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
    let resignationsTable;

    $(document).ready(function () {
        resignationsTable = $('#dataTable-resignations').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("exit-management.list") }}',
                data: function (d) {
                    d.status = $('#filterStatus').val();
                }
            },
            columns: [
                { data: 'employee.full_name', name: 'employee.full_name' },
                { data: 'notice_date', name: 'notice_date', render: function (data) {
                    return data ? moment(data).format('DD/MM/YYYY') : '-';
                }},
                { data: 'last_working_date', name: 'last_working_date', render: function (data) {
                    return data ? moment(data).format('DD/MM/YYYY') : '-';
                }},
                { data: 'status', name: 'status', render: function (data) {
                    const badges = { pending: 'warning', approved: 'success', rejected: 'danger', cancelled: 'secondary', cleared: 'info' };
                    return `<span class="badge bg-${badges[data] || 'secondary'}">${ucfirst(data)}</span>`;
                }},
                { data: null, name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    let buttons = '<div class="btn-group btn-group-sm">' +
                        '<button class="btn btn-info" onclick="viewResignation(' + row.id + ')" title="View"><i class="bi bi-eye"></i></button>';
                    if (isAdmin) {
                        if (row.status === 'pending') {
                            buttons += '<button class="btn btn-success" onclick="approveResignation(' + row.id + ')" title="Approve"><i class="bi bi-check-lg"></i></button>' +
                                '<button class="btn btn-warning" onclick="rejectResignation(' + row.id + ')" title="Reject"><i class="bi bi-x-lg"></i></button>';
                        }
                        buttons += '<button class="btn btn-danger" onclick="deleteResignation(' + row.id + ')" title="Delete"><i class="bi bi-trash"></i></button>';
                    } else if (row.created_by === {{ auth()->id() }} && row.status === 'pending') {
                        buttons += '<button class="btn btn-primary" onclick="editResignation(' + row.id + ')" title="Edit"><i class="bi bi-pencil"></i></button>';
                    }
                    buttons += '</div>';
                    return buttons;
                }}
            ],
            responsive: true,
            order: [[1, 'desc']]
        });
        window.resignationsTable = resignationsTable;

        $('#filterStatus').change(function () {
            resignationsTable.draw();
        });
    });

    function openSubmitModal() {
        const html = `
            <form id="resignationForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Notice Date <span class="text-danger">*</span></label>
                        <input type="text" name="notice_date" class="form-control flatpickr" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last Working Day</label>
                        <input type="text" name="last_working_date" class="form-control flatpickr">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Reason Category</label>
                        <select name="reason_category" class="form-select">
                            <option value="">Select</option>
                            <option value="personal">Personal</option>
                            <option value="career">Career</option>
                            <option value="health">Health</option>
                            <option value="relocation">Relocation</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Notice Period (Days)</label>
                        <input type="number" name="notice_period_days" class="form-control" min="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Accrued Leave Payout</label>
                        <input type="number" name="accrued_leave_payout" class="form-control" step="0.01" min="0">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Reason</label>
                        <textarea name="reason" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Submit Resignation</button>
                    </div>
                </div>
            </form>
        `;
        $('#globalModalLabel').text('Submit Resignation');
        $('#globalModalBody').html(html);
        $('#globalModal').modal('show');
        flatpickr('.flatpickr', { dateFormat: 'Y-m-d', allowInput: true });

        $('#globalModal').off('hidden.bs.modal').on('hidden.bs.modal', function () {
            $('#globalModal').off('submit');
        });
    }

    $(document).on('submit', '#resignationForm', function (e) {
        e.preventDefault();
        const formData = $(this).serialize();
        $.ajax({
            url: '{{ route("exit-management.store") }}',
            type: 'POST',
            data: formData,
            success: function () {
                $('#globalModal').modal('hide');
                resignationsTable.draw();
                App.toast('Resignation submitted successfully', 'success');
            },
            error: function (xhr) {
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();
                    Object.keys(errors).forEach(function (field) {
                        const input = $(`[name="${field}"]`);
                        input.addClass('is-invalid');
                        input.after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
                    });
                } else {
                    App.toast('Error submitting resignation', 'error');
                }
            }
        });
    });

    function viewResignation(id) {
        $.get('{{ url("exit-management") }}/' + id, function (html) {
            $('#globalModalLabel').text('View Resignation');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function editResignation(id) {
        $.get('{{ url("exit-management") }}/' + id + '/edit', function (html) {
            $('#globalModalLabel').text('Edit Resignation');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function approveResignation(id) {
        Swal.fire({
            title: 'Approve Resignation?',
            text: 'Are you sure you want to approve this resignation?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, approve',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("exit-management") }}/' + id + '/approve',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function () {
                        resignationsTable.draw();
                        App.toast('Resignation approved', 'success');
                    },
                    error: function () {
                        App.toast('Error approving resignation', 'error');
                    }
                });
            }
        });
    }

    function rejectResignation(id) {
        Swal.fire({
            title: 'Reject Resignation?',
            text: 'Enter rejection reason (optional):',
            icon: 'warning',
            input: 'textarea',
            inputPlaceholder: 'Rejection reason...',
            showCancelButton: true,
            confirmButtonText: 'Reject',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("exit-management") }}/' + id + '/reject',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        rejection_reason: result.value || ''
                    },
                    success: function () {
                        resignationsTable.draw();
                        App.toast('Resignation rejected', 'error');
                    },
                    error: function () {
                        App.toast('Error rejecting resignation', 'error');
                    }
                });
            }
        });
    }

    function deleteResignation(id) {
        $('#confirmDeleteBtn').data('id', id);
        $('#deleteModal').modal('show');
    }

    $(document).on('click', '#confirmDeleteBtn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("exit-management") }}/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                $('#deleteModal').modal('hide');
                resignationsTable.draw();
                App.toast('Resignation deleted', 'success');
            },
            error: function () {
                App.toast('Error deleting resignation', 'error');
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
