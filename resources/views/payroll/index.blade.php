@extends('layouts.master')

@section('title', 'Payroll')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0">Payroll Management</h5>
        <div class="d-flex gap-2">
            <select id="filterMonth" class="form-select" style="width: auto;">
                <option value="">All Months</option>
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endforeach
            </select>
            <select id="filterYear" class="form-select" style="width: auto;">
                <option value="">All Years</option>
                @foreach($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
            <select id="filterStatus" class="form-select" style="width: auto;">
                <option value="">All Status</option>
                <option value="draft">Draft</option>
                <option value="generated">Generated</option>
                <option value="processing">Processing</option>
                <option value="paid">Paid</option>
                <option value="cancelled">Cancelled</option>
            </select>
            @if(auth()->user()->hasRole(['Owner', 'Admin']))
                <button type="button" class="btn btn-success" id="processSelectedBtn" disabled>
                    <i class="bi bi-check2-square"></i> Process Selected
                </button>
                <button type="button" class="btn btn-primary" onclick="generatePayroll()">
                    <i class="bi bi-plus-lg"></i> Generate Payroll
                </button>
            @endif
        </div>
    </div>
    <div class="card-body">
        <table id="dataTable-payroll" class="table table-hover">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Employee</th>
                    <th>Period</th>
                    <th>Basic Salary</th>
                    <th>Earnings</th>
                    <th>Deductions</th>
                    <th>Net Salary</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade" id="processModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Process Payroll</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Update status for <strong id="processCount">0</strong> selected payroll record(s).</p>
                <div class="mb-3">
                    <label class="form-label">Action <span class="text-danger">*</span></label>
                    <select id="processStatus" class="form-select">
                        <option value="paid">Mark as Paid</option>
                        <option value="cancelled">Cancel</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="confirmProcessBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const isAdmin = @json(auth()->user()->hasRole(['Owner', 'Admin']));
    let payrollTable;

    $(document).ready(function () {
        payrollTable = $('#dataTable-payroll').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("payroll.list") }}',
                data: function (d) {
                    d.month = $('#filterMonth').val();
                    d.year = $('#filterYear').val();
                    d.status = $('#filterStatus').val();
                }
            },
            columns: [
                { data: null, orderable: false, searchable: false, render: function(data, type, row) {
                    return '<input type="checkbox" class="row-checkbox" value="' + row.id + '">';
                }},
                { data: 'employee.full_name', name: 'employee.full_name', orderable: false },
                { data: 'payroll_period', name: 'payroll_period' },
                { data: 'basic_salary', name: 'basic_salary', render: function (data) {
                    return data != null ? formatCurrency(data) : '-';
                }},
                { data: 'total_earnings', name: 'total_earnings', render: function (data) {
                    return data != null ? formatCurrency(data) : '-';
                }},
                { data: 'total_deductions', name: 'total_deductions', render: function (data) {
                    return data != null ? formatCurrency(data) : '-';
                }},
                { data: 'net_salary', name: 'net_salary', render: function (data) {
                    return data != null ? formatCurrency(data) : '-';
                }},
                { data: 'status', name: 'status', render: function (data) {
                    const badges = { draft: 'secondary', generated: 'success', processing: 'warning', paid: 'info', cancelled: 'danger' };
                    return `<span class="badge bg-${badges[data] || 'secondary'}">${ucfirst(data)}</span>`;
                }},
                { data: null, name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    let buttons = '<div class="btn-group btn-group-sm">' +
                        '<button class="btn btn-info" onclick="viewPayslip(' + row.id + ')" title="View"><i class="bi bi-eye"></i></button>';
                    if (isAdmin) {
                        if (row.status === 'draft' || row.status === 'generated') {
                            buttons += '<button class="btn btn-success" onclick="markAsPaid(' + row.id + ')" title="Mark as Paid"><i class="bi bi-check-lg"></i></button>';
                        }
                        buttons += '<button class="btn btn-danger" onclick="deletePayroll(' + row.id + ')" title="Delete"><i class="bi bi-trash"></i></button>';
                    }
                    buttons += '</div>';
                    return buttons;
                } }
            ],
            responsive: true,
            order: [[2, 'desc']],
            columnDefs: [{ targets: 0, orderable: false }]
        });
        window.payrollTable = payrollTable;

        $('#filterMonth, #filterYear, #filterStatus').change(function () {
            payrollTable.draw();
        });

        $('#selectAll').change(function () {
            $('.row-checkbox').prop('checked', $(this).is(':checked'));
            updateProcessButton();
        });

        $(document).on('change', '.row-checkbox', function () {
            updateProcessButton();
        });
    });

    function updateProcessButton() {
        const checked = $('.row-checkbox:checked').length;
        $('#processSelectedBtn').prop('disabled', checked === 0);
    }

    $('#processSelectedBtn').click(function () {
        const ids = $('.row-checkbox:checked').map(function () { return $(this).val(); }).get();
        $('#processCount').text(ids.length);
        $('#processModal').data('ids', ids).modal('show');
    });

    $('#confirmProcessBtn').click(function () {
        const ids = $('#processModal').data('ids');
        const status = $('#processStatus').val();
        const btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Processing...');

        $.ajax({
            url: '{{ route("payroll.bulk-process") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                payroll_ids: ids,
                status: status
            },
            success: function () {
                $('#processModal').modal('hide');
                payrollTable.draw();
                App.toast('Payroll processed successfully.', 'success');
            },
            error: function () {
                App.toast('Error processing payroll.', 'error');
            },
            complete: function () {
                btn.prop('disabled', false).text('Confirm');
            }
        });
    });

    function markAsPaid(id) {
        $.ajax({
            url: '{{ route("payroll.bulk-process") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                payroll_ids: [id],
                status: 'paid'
            },
            success: function () {
                payrollTable.draw();
                App.toast('Payroll marked as paid.', 'success');
            },
            error: function () {
                App.toast('Error processing payroll.', 'error');
            }
        });
    }

    function generatePayroll() {
        $.get('{{ route("payroll.create") }}', function (html) {
            $('#globalModalLabel').text('Generate Payroll');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function viewPayslip(id) {
        $.get('{{ url("payroll") }}/' + id, function (html) {
            $('#globalModalLabel').text('Payslip');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function deletePayroll(id) {
        $('#confirmDeleteBtn').data('id', id);
        $('#deleteModal').modal('show');
    }

    $(document).on('click', '#confirmDeleteBtn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("payroll") }}/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                $('#deleteModal').modal('hide');
                payrollTable.draw();
                App.toast('Payroll record deleted successfully', 'success');
            },
            error: function () {
                App.toast('Error deleting payroll record', 'error');
            }
        });
    });

    function formatCurrency(amount) {
        return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount);
    }

    function ucfirst(str) {
        if (!str) return '';
        str = String(str);
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
</script>
@endpush
