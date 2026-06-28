@extends('layouts.master')

@section('title', 'Expenses')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0">Expense Management</h5>
        <button type="button" class="btn btn-primary" onclick="openCreateModal()">
            <i class="bi bi-plus-lg"></i> Add Expense
        </button>
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
                    <option value="paid">Paid</option>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filterCategory" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" id="filterDateFrom" class="form-control flatpickr" placeholder="From date">
            </div>
            <div class="col-md-3">
                <input type="text" id="filterDateTo" class="form-control flatpickr" placeholder="To date">
            </div>
        </div>

        <table id="dataTable-expenses" class="table table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Receipt</th>
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
    let expensesTable;

    $(document).ready(function () {
        flatpickr('.flatpickr', {
            dateFormat: 'Y-m-d',
            allowInput: true
        });

        expensesTable = $('#dataTable-expenses').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("expenses.list") }}',
                data: function (d) {
                    d.status = $('#filterStatus').val();
                    d.category_id = $('#filterCategory').val();
                    d.date_from = $('#filterDateFrom').val();
                    d.date_to = $('#filterDateTo').val();
                }
            },
            columns: [
                { data: 'expense_date', name: 'expense_date', render: function (data) {
                    return data ? moment(data).format('DD/MM/YYYY') : '-';
                }},
                { data: 'category.name', name: 'category.name', defaultContent: '-' },
                { data: 'description', name: 'description', render: function (data) {
                    return data ? data.substring(0, 40) + (data.length > 40 ? '...' : '') : '-';
                }},
                { data: 'amount', name: 'amount', render: function (data) {
                    return data ? currency(data) : '-';
                }},
                { data: 'receipt_path', name: 'receipt_path', orderable: false, searchable: false, render: function (data) {
                    if (!data) return '<span class="text-muted">-</span>';
                    return '<a href="' + '{{ Storage::url("") }}' + data + '" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-paperclip"></i></a>';
                }},
                { data: 'status', name: 'status', render: function (data) {
                    const badges = { draft: 'secondary', pending: 'warning', approved: 'success', rejected: 'danger', paid: 'info' };
                    return '<span class="badge bg-' + (badges[data] || 'secondary') + '">' + ucfirst(data) + '</span>';
                }},
                { data: null, name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    let buttons = '<div class="btn-group btn-group-sm">' +
                        '<button class="btn btn-info" onclick="viewExpense(' + row.id + ')" title="View"><i class="bi bi-eye"></i></button>';
                    if (isAdmin) {
                        buttons += '<button class="btn btn-primary" onclick="openEditModal(' + row.id + ')" title="Edit"><i class="bi bi-pencil"></i></button>';
                        if (row.status === 'pending' || row.status === 'draft') {
                            buttons += '<button class="btn btn-success" onclick="approveExpense(' + row.id + ')" title="Approve"><i class="bi bi-check-lg"></i></button>' +
                                '<button class="btn btn-warning" onclick="rejectExpense(' + row.id + ')" title="Reject"><i class="bi bi-x-lg"></i></button>';
                        }
                        if (row.status === 'approved') {
                            buttons += '<button class="btn btn-secondary" onclick="payExpense(' + row.id + ')" title="Mark Paid"><i class="bi bi-cash"></i></button>';
                        }
                        buttons += '<button class="btn btn-danger" onclick="deleteExpense(' + row.id + ')" title="Delete"><i class="bi bi-trash"></i></button>';
                    } else {
                        if (row.status === 'draft' || row.status === 'pending') {
                            buttons += '<button class="btn btn-primary" onclick="openEditModal(' + row.id + ')" title="Edit"><i class="bi bi-pencil"></i></button>';
                        }
                    }
                    buttons += '</div>';
                    return buttons;
                } }
            ],
            responsive: true,
            order: [[0, 'desc']]
        });
        window.expensesTable = expensesTable;

        $('#filterStatus, #filterCategory, #filterDateFrom, #filterDateTo').change(function () {
            expensesTable.draw();
        });
    });

    function openCreateModal() {
        $.get('{{ route("expenses.create") }}', function (html) {
            $('#globalModalLabel').text('Add Expense');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function openEditModal(id) {
        $.get('{{ url("expenses") }}/' + id + '/edit', function (html) {
            $('#globalModalLabel').text('Edit Expense');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function viewExpense(id) {
        $.get('{{ url("expenses") }}/' + id, function (html) {
            $('#globalModalLabel').text('View Expense');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function approveExpense(id) {
        Swal.fire({
            title: 'Approve Expense?',
            text: 'Are you sure you want to approve this expense?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, approve',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("expenses") }}/' + id + '/approve',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function () {
                        expensesTable.draw();
                        App.toast('Expense approved successfully', 'success');
                    },
                    error: function () {
                        App.toast('Error approving expense', 'error');
                    }
                });
            }
        });
    }

    function rejectExpense(id) {
        Swal.fire({
            title: 'Reject Expense?',
            text: 'Enter rejection reason (optional)',
            icon: 'warning',
            input: 'textarea',
            inputPlaceholder: 'Rejection reason...',
            inputAttributes: { 'maxlength': '500' },
            showCancelButton: true,
            confirmButtonText: 'Yes, reject',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("expenses") }}/' + id + '/reject',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        rejection_reason: result.value || ''
                    },
                    success: function () {
                        expensesTable.draw();
                        App.toast('Expense rejected', 'error');
                    },
                    error: function () {
                        App.toast('Error rejecting expense', 'error');
                    }
                });
            }
        });
    }

    function payExpense(id) {
        Swal.fire({
            title: 'Mark as Paid?',
            text: 'Are you sure you want to mark this expense as paid?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, mark paid',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("expenses") }}/' + id + '/pay',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function () {
                        expensesTable.draw();
                        App.toast('Expense marked as paid', 'success');
                    },
                    error: function () {
                        App.toast('Error marking expense as paid', 'error');
                    }
                });
            }
        });
    }

    function deleteExpense(id) {
        $('#confirmDeleteBtn').data('id', id);
        $('#deleteModal').modal('show');
    }

    $(document).on('click', '#confirmDeleteBtn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("expenses") }}/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                $('#deleteModal').modal('hide');
                expensesTable.draw();
                App.toast('Expense deleted successfully', 'success');
            },
            error: function () {
                App.toast('Error deleting expense', 'error');
            }
        });
    });

    function ucfirst(str) {
        if (!str) return '';
        str = String(str);
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    function currency(amount) {
        return '{{ setting("currency_symbol", "$") }}' + parseFloat(amount).toFixed(2);
    }
</script>
@endpush
