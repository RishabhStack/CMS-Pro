@extends('layouts.master')

@section('title', 'Assets')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0">Asset Management</h5>
        @if(auth()->user()->hasRole(['Owner', 'Admin']))
            <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                <i class="bi bi-plus-lg"></i> Add Asset
            </button>
        @endif
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <select id="filterType" class="form-select">
                    <option value="">All Types</option>
                    <option value="laptop">Laptop</option>
                    <option value="phone">Phone</option>
                    <option value="accessory">Accessory</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="col-md-4">
                <select id="filterStatus" class="form-select">
                    <option value="">All Status</option>
                    <option value="available">Available</option>
                    <option value="assigned">Assigned</option>
                    <option value="under_repair">Under Repair</option>
                    <option value="disposed">Disposed</option>
                </select>
            </div>
        </div>

        <table id="dataTable-assets" class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Brand / Model</th>
                    <th>Serial #</th>
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
    let assetsTable;

    $(document).ready(function () {
        assetsTable = $('#dataTable-assets').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("assets.list") }}',
                data: function (d) {
                    d.type = $('#filterType').val();
                    d.status = $('#filterStatus').val();
                }
            },
            columns: [
                { data: 'name', name: 'name' },
                { data: 'type', name: 'type', render: function (data) {
                    const icons = { laptop: '<i class="bi bi-laptop"></i>', phone: '<i class="bi bi-phone"></i>', accessory: '<i class="bi bi-plug"></i>', other: '<i class="bi bi-box"></i>' };
                    return (icons[data] || '') + ' ' + ucfirst(data);
                }},
                { data: null, name: 'brand', orderable: false, searchable: false, render: function(data, type, row) {
                    const parts = [];
                    if (row.brand) parts.push(row.brand);
                    if (row.model) parts.push(row.model);
                    return parts.length ? parts.join(' / ') : '-';
                }},
                { data: 'serial_number', name: 'serial_number', render: function (data) {
                    return data || '-';
                }},
                { data: 'status', name: 'status', render: function (data) {
                    const badges = { available: 'success', assigned: 'primary', under_repair: 'warning', disposed: 'danger' };
                    const labels = { available: 'Available', assigned: 'Assigned', under_repair: 'Under Repair', disposed: 'Disposed' };
                    return '<span class="badge bg-' + (badges[data] || 'secondary') + '">' + (labels[data] || ucfirst(data)) + '</span>';
                }},
                { data: 'current_assignment.employee.full_name', name: 'current_assignment.employee.full_name', defaultContent: '<span class="text-muted">-</span>' },
                { data: null, name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    let buttons = '<div class=\"btn-group btn-group-sm\">' +
                        '<button class=\"btn btn-info\" onclick=\"viewAsset(' + row.id + ')\" title=\"View\"><i class=\"bi bi-eye\"></i></button>';
                    if (isAdmin) {
                        buttons += '<button class=\"btn btn-primary\" onclick=\"openEditModal(' + row.id + ')\" title=\"Edit\"><i class=\"bi bi-pencil\"></i></button>';
                        if (row.status === 'available') {
                            buttons += '<button class=\"btn btn-success\" onclick=\"openAssignModal(' + row.id + ')\" title=\"Assign\"><i class=\"bi bi-person-plus\"></i></button>';
                        }
                        if (row.status === 'assigned') {
                            buttons += '<button class=\"btn btn-warning\" onclick=\"openReturnModal(' + row.id + ')\" title=\"Return\"><i class=\"bi bi-arrow-return-left\"></i></button>';
                        }
                        buttons += '<button class=\"btn btn-danger\" onclick=\"deleteAsset(' + row.id + ')\" title=\"Delete\"><i class=\"bi bi-trash\"></i></button>';
                    }
                    buttons += '</div>';
                    return buttons;
                } }
            ],
            responsive: true,
            order: [[0, 'asc']]
        });
        window.assetsTable = assetsTable;

        $('#filterType, #filterStatus').change(function () {
            assetsTable.draw();
        });
    });

    function openCreateModal() {
        $.get('{{ route("assets.create") }}', function (html) {
            $('#globalModalLabel').text('Add Asset');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function openEditModal(id) {
        $.get('{{ url("assets") }}/' + id + '/edit', function (html) {
            $('#globalModalLabel').text('Edit Asset');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function viewAsset(id) {
        $.get('{{ url("assets") }}/' + id, function (html) {
            $('#globalModalLabel').text('View Asset');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function openAssignModal(id) {
        $.get('{{ url("assets") }}/' + id + '/assign-form', function (html) {
            $('#globalModalLabel').text('Assign Asset');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function openReturnModal(id) {
        $.get('{{ url("assets") }}/' + id + '/return-form', function (html) {
            $('#globalModalLabel').text('Return Asset');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function deleteAsset(id) {
        $('#confirmDeleteBtn').data('id', id);
        $('#deleteModal').modal('show');
    }

    $(document).on('click', '#confirmDeleteBtn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("assets") }}/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                $('#deleteModal').modal('hide');
                assetsTable.draw();
                App.toast('Asset deleted successfully', 'success');
            },
            error: function () {
                App.toast('Error deleting asset', 'error');
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
