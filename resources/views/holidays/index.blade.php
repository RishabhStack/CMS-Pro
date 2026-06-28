@extends('layouts.master')

@section('title', 'Holiday Calendar')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0">Holiday Calendar</h5>
        <div class="d-flex gap-2">
            <select id="filterYear" class="form-select" style="width: auto;">
                @foreach($years as $year)
                    <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
            @if(auth()->user()->hasRole(['Owner', 'Admin']))
                <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                    <i class="bi bi-plus-lg"></i> Add Holiday
                </button>
            @endif
        </div>
    </div>
    <div class="card-body">
        <table id="dataTable-holidays" class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Type</th>
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
    let holidayTable;

    $(document).ready(function () {
        holidayTable = $('#dataTable-holidays').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("holidays.list") }}',
                data: function (d) {
                    d.year = $('#filterYear').val();
                }
            },
            columns: [
                { data: 'name', name: 'name' },
                { data: 'date', name: 'date', render: function (data) {
                    return data ? moment(data).format('DD/MM/YYYY') : '-';
                }},
                { data: 'day', name: 'day', render: function (data) {
                    return data ? data : moment(data, 'YYYY-MM-DD').format('dddd');
                }},
                { data: 'type', name: 'type', render: function (data) {
                    return data ? `<span class="badge bg-info">${data}</span>` : '-';
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
                            '<button class=\"btn btn-danger\" onclick=\"deleteHoliday(' + row.id + ')\" title=\"Delete\"><i class=\"bi bi-trash\"></i></button>';
                    }
                    buttons += '</div>';
                    return buttons;
                } }
            ],
            responsive: true,
            order: [[1, 'desc']]
        });
        window.holidayTable = holidayTable;

        $('#filterYear').change(function () {
            holidayTable.draw();
        });
    });

    function openCreateModal() {
        $.get('{{ route("holidays.create") }}', function (html) {
            $('#globalModalLabel').text('Add Holiday');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function openEditModal(id) {
        $.get('{{ url("holidays") }}/' + id + '/edit', function (html) {
            $('#globalModalLabel').text('Edit Holiday');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function deleteHoliday(id) {
        $('#confirmDeleteBtn').data('id', id);
        $('#deleteModal').modal('show');
    }

    $(document).on('click', '#confirmDeleteBtn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("holidays") }}/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                $('#deleteModal').modal('hide');
                holidayTable.draw();
                App.toast('Holiday deleted successfully', 'success');
            },
            error: function () {
                App.toast('Error deleting holiday', 'error');
            }
        });
    });
</script>
@endpush
