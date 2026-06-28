@extends('layouts.master')

@section('title', 'Salary Components')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Salary Components</h5>
        @if(auth()->user()->hasRole(['Owner', 'Admin']))
            <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                <i class="bi bi-plus-lg"></i> Add Component
            </button>
        @endif
    </div>
    <div class="card-body">
        <table id="dataTable-salary-components" class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Value Type</th>
                    <th>Default Value</th>
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
    let salaryComponentTable;

    $(document).ready(function () {
        salaryComponentTable = $('#dataTable-salary-components').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("salary-components.list") }}',
            columns: [
                { data: 'id', name: 'id', visible: false },
                { data: 'name', name: 'name' },
                { data: 'type', name: 'type', render: function (data) {
                    const badges = { earning: 'success', deduction: 'danger' };
                    return `<span class="badge bg-${badges[data] || 'secondary'}">${ucfirst(data)}</span>`;
                }},
                { data: 'value_type', name: 'value_type', render: function (data) {
                    return data === 'percentage' ? 'Percentage (%)' : 'Fixed Amount';
                }},
                { data: 'default_value', name: 'default_value', render: function (data) {
                    return data ? data : '0';
                }},
                { data: 'status', name: 'status', render: function (data) {
                    return data === 'active'
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                }},
                { data: null, name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    let buttons = '<div class=\"btn-group btn-group-sm\">';
                    if (isAdmin) {
                        buttons += '<button class=\"btn btn-primary\" onclick=\"openEditModal(' + row.id + ')\" title=\"Edit\"><i class=\"bi bi-pencil\"></i></button>' +
                            '<button class=\"btn btn-danger\" onclick=\"deleteSalaryComponent(' + row.id + ')\" title=\"Delete\"><i class=\"bi bi-trash\"></i></button>';
                    }
                    buttons += '</div>';
                    return buttons;
                } }
            ],
            responsive: true,
            order: [[0, 'desc']]
        });
        window.salaryComponentTable = salaryComponentTable;
    });

    function openCreateModal() {
        $.get('{{ route("salary-components.create") }}', function (html) {
            $('#globalModalLabel').text('Add Salary Component');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function openEditModal(id) {
        $.get('{{ url("salary-components") }}/' + id + '/edit', function (html) {
            $('#globalModalLabel').text('Edit Salary Component');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function deleteSalaryComponent(id) {
        $('#confirmDeleteBtn').data('id', id);
        $('#deleteModal').modal('show');
    }

    $(document).on('click', '#confirmDeleteBtn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("salary-components") }}/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                $('#deleteModal').modal('hide');
                salaryComponentTable.draw();
                App.toast('Salary component deleted successfully', 'success');
            },
            error: function () {
                App.toast('Error deleting salary component', 'error');
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
