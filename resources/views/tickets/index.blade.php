@extends('layouts.master')

@section('title', 'Helpdesk Tickets')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0">Helpdesk Tickets</h5>
        <button type="button" class="btn btn-primary" onclick="openCreateModal()">
            <i class="bi bi-plus-lg"></i> Create Ticket
        </button>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <select id="filterStatus" class="form-select">
                    <option value="">All Status</option>
                    <option value="open">Open</option>
                    <option value="in_progress">In Progress</option>
                    <option value="resolved">Resolved</option>
                    <option value="closed">Closed</option>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filterPriority" class="form-select">
                    <option value="">All Priority</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    <option value="critical">Critical</option>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filterCategory" class="form-select">
                    <option value="">All Category</option>
                    <option value="it">IT</option>
                    <option value="hr">HR</option>
                    <option value="administration">Administration</option>
                    <option value="other">Other</option>
                </select>
            </div>
        </div>

        <table id="dataTable-tickets" class="table table-hover">
            <thead>
                <tr>
                    <th>Ticket#</th>
                    <th>Subject</th>
                    <th>Category</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Assigned To</th>
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
    let ticketsTable;

    $(document).ready(function () {
        ticketsTable = $('#dataTable-tickets').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("tickets.list") }}',
                data: function (d) {
                    d.status = $('#filterStatus').val();
                    d.priority = $('#filterPriority').val();
                    d.category = $('#filterCategory').val();
                }
            },
            columns: [
                { data: 'ticket_number', name: 'ticket_number' },
                { data: 'subject', name: 'subject' },
                { data: 'category', name: 'category', render: function (data) {
                    return data ? ucfirst(data) : '-';
                }},
                { data: 'priority', name: 'priority', render: function (data) {
                    const badges = { low: 'secondary', medium: 'info', high: 'warning', critical: 'danger' };
                    return `<span class="badge bg-${badges[data] || 'secondary'}">${ucfirst(data)}</span>`;
                }},
                { data: 'status', name: 'status', render: function (data) {
                    const badges = { open: 'success', in_progress: 'info', resolved: 'primary', closed: 'secondary' };
                    return `<span class="badge bg-${badges[data] || 'secondary'}">${data ? data.replace('_', ' ') : ''}</span>`;
                }},
                { data: 'assignee', name: 'assigned_to', render: function (data) {
                    return data ? data.first_name + ' ' + data.last_name : '<span class="text-muted">Unassigned</span>';
                }},
                { data: null, name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    let buttons = '<div class="btn-group btn-group-sm">' +
                        '<button class="btn btn-info" onclick="viewTicket(' + row.id + ')" title="View"><i class="bi bi-eye"></i></button>';
                    if (isAdmin) {
                        buttons += '<button class="btn btn-primary" onclick="assignTicket(' + row.id + ')" title="Assign"><i class="bi bi-person-plus"></i></button>';
                        if (row.status !== 'resolved' && row.status !== 'closed') {
                            buttons += '<button class="btn btn-success" onclick="resolveTicket(' + row.id + ')" title="Resolve"><i class="bi bi-check-circle"></i></button>';
                        }
                        if (row.status !== 'closed') {
                            buttons += '<button class="btn btn-secondary" onclick="closeTicket(' + row.id + ')" title="Close"><i class="bi bi-x-circle"></i></button>';
                        }
                        if (row.status === 'resolved' || row.status === 'closed') {
                            buttons += '<button class="btn btn-warning" onclick="reopenTicket(' + row.id + ')" title="Reopen"><i class="bi bi-arrow-counterclockwise"></i></button>';
                        }
                        buttons += '<button class="btn btn-danger" onclick="deleteTicket(' + row.id + ')" title="Delete"><i class="bi bi-trash"></i></button>';
                    }
                    buttons += '</div>';
                    return buttons;
                }}
            ],
            responsive: true,
            order: [[0, 'desc']]
        });
        window.ticketsTable = ticketsTable;

        $('#filterStatus, #filterPriority, #filterCategory').change(function () {
            ticketsTable.draw();
        });
    });

    function openCreateModal() {
        const html = `
            <form id="ticketForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Subject <span class="text-danger">*</span></label>
                        <input type="text" name="subject" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            <option value="">Select</option>
                            <option value="it">IT</option>
                            <option value="hr">HR</option>
                            <option value="administration">Administration</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Priority <span class="text-danger">*</span></label>
                        <select name="priority" class="form-select" required>
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Create Ticket</button>
                    </div>
                </div>
            </form>
        `;
        $('#globalModalLabel').text('Create Ticket');
        $('#globalModalBody').html(html);
        $('#globalModal').modal('show');
    }

    $(document).on('submit', '#ticketForm', function (e) {
        e.preventDefault();
        const formData = $(this).serialize();
        $.ajax({
            url: '{{ route("tickets.store") }}',
            type: 'POST',
            data: formData,
            success: function () {
                $('#globalModal').modal('hide');
                ticketsTable.draw();
                App.toast('Ticket created successfully', 'success');
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
                    App.toast('Error creating ticket', 'error');
                }
            }
        });
    });

    function viewTicket(id) {
        $.get('{{ url("tickets") }}/' + id, function (html) {
            $('#globalModalLabel').text('View Ticket');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function assignTicket(id) {
        const html = `
            <form id="assignForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Assign To <span class="text-danger">*</span></label>
                        <select name="assigned_to" class="form-select" required>
                            <option value="">Select User</option>
                        </select>
                    </div>
                </div>
            </form>
        `;
        $('#globalModalLabel').text('Assign Ticket');
        $('#globalModalBody').html(html);
        $('#globalModal').modal('show');

        $.get('{{ url("employees/list") }}', function (data) {
            const sel = document.querySelector('select[name="assigned_to"]');
            data.data?.forEach(u => {
                sel.innerHTML += `<option value="${u.user_id || u.id}">${u.full_name || (u.first_name + ' ' + u.last_name)}</option>`;
            });
        });

        $(document).off('submit', '#assignForm').on('submit', '#assignForm', function (e) {
            e.preventDefault();
            const formData = $(this).serialize();
            $.ajax({
                url: '{{ url("tickets") }}/' + id + '/assign',
                type: 'POST',
                data: formData,
                success: function () {
                    $('#globalModal').modal('hide');
                    ticketsTable.draw();
                    App.toast('Ticket assigned', 'success');
                },
                error: function () {
                    App.toast('Error assigning ticket', 'error');
                }
            });
        });
    }

    function resolveTicket(id) {
        Swal.fire({
            title: 'Resolve Ticket?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, resolve',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("tickets") }}/' + id + '/resolve',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function () {
                        ticketsTable.draw();
                        App.toast('Ticket resolved', 'success');
                    },
                    error: function () {
                        App.toast('Error resolving ticket', 'error');
                    }
                });
            }
        });
    }

    function closeTicket(id) {
        Swal.fire({
            title: 'Close Ticket?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, close',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("tickets") }}/' + id + '/close',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function () {
                        ticketsTable.draw();
                        App.toast('Ticket closed', 'success');
                    },
                    error: function () {
                        App.toast('Error closing ticket', 'error');
                    }
                });
            }
        });
    }

    function reopenTicket(id) {
        Swal.fire({
            title: 'Reopen Ticket?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, reopen',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("tickets") }}/' + id + '/reopen',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function () {
                        ticketsTable.draw();
                        App.toast('Ticket reopened', 'success');
                    },
                    error: function () {
                        App.toast('Error reopening ticket', 'error');
                    }
                });
            }
        });
    }

    function deleteTicket(id) {
        $('#confirmDeleteBtn').data('id', id);
        $('#deleteModal').modal('show');
    }

    $(document).on('click', '#confirmDeleteBtn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("tickets") }}/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                $('#deleteModal').modal('hide');
                ticketsTable.draw();
                App.toast('Ticket deleted', 'success');
            },
            error: function () {
                App.toast('Error deleting ticket', 'error');
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
