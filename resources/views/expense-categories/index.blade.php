@extends('layouts.master')

@section('title', 'Expense Categories')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Expense Categories</h5>
        @if(auth()->user()->hasRole(['Owner', 'Admin']))
            <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                <i class="bi bi-plus-lg"></i> Add Category
            </button>
        @endif
    </div>
    <div class="card-body">
        <table id="dataTable-expense-categories" class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Max Amount</th>
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
    let expenseCategoryTable;

    $(document).ready(function () {
        expenseCategoryTable = $('#dataTable-expense-categories').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("expense-categories.list") }}',
            columns: [
                { data: 'name', name: 'name' },
                { data: 'description', name: 'description', render: function (data) {
                    return data ? data.substring(0, 50) + (data.length > 50 ? '...' : '') : '-';
                }},
                { data: 'max_amount', name: 'max_amount', render: function (data) {
                    return data ? currency(data) : '-';
                }},
                { data: 'status', name: 'status', render: function (data) {
                    return data === 'active'
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                }},
                { data: null, name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    let buttons = '<div class="btn-group btn-group-sm">';
                    if (isAdmin) {
                        buttons += '<button class="btn btn-primary" onclick="openEditModal(' + row.id + ')" title="Edit"><i class="bi bi-pencil"></i></button>' +
                            '<button class="btn btn-danger" onclick="deleteCategory(' + row.id + ')" title="Delete"><i class="bi bi-trash"></i></button>';
                    }
                    buttons += '</div>';
                    return buttons;
                } }
            ],
            responsive: true,
            order: [[0, 'asc']]
        });
        window.expenseCategoryTable = expenseCategoryTable;
    });

    function openCreateModal() {
        $.get('{{ route("expense-categories.create") }}', function (html) {
            $('#globalModalLabel').text('Add Expense Category');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function openEditModal(id) {
        $.get('{{ url("expense-categories") }}/' + id + '/edit', function (html) {
            $('#globalModalLabel').text('Edit Expense Category');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function deleteCategory(id) {
        $('#confirmDeleteBtn').data('id', id);
        $('#deleteModal').modal('show');
    }

    $(document).on('click', '#confirmDeleteBtn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("expense-categories") }}/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                $('#deleteModal').modal('hide');
                expenseCategoryTable.draw();
                App.toast('Expense category deleted successfully', 'success');
            },
            error: function () {
                App.toast('Error deleting expense category', 'error');
            }
        });
    });
</script>
@endpush
