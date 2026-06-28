@extends('layouts.master')

@section('title', 'Announcements')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Announcements</h5>
        @if(auth()->user()->hasRole(['Owner', 'Admin']))
            <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                <i class="bi bi-plus-lg"></i> Create Announcement
            </button>
        @endif
    </div>
    <div class="card-body">
        <table id="dataTable-announcements" class="table table-hover">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Priority</th>
                    <th>Published At</th>
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
    let announcementTable;

    $(document).ready(function () {
        announcementTable = $('#dataTable-announcements').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("announcements.list") }}',
            columns: [
                { data: 'title', name: 'title' },
                { data: 'type', name: 'type', render: function (data) {
                    const badges = { general: 'secondary', holiday: 'success', event: 'info', policy: 'warning' };
                    return data ? `<span class="badge bg-${badges[data] || 'secondary'}">${ucfirst(data)}</span>` : '-';
                }},
                { data: 'priority', name: 'priority', render: function (data) {
                    const badges = { high: 'danger', medium: 'warning', low: 'info' };
                    return `<span class="badge bg-${badges[data] || 'secondary'}">${ucfirst(data)}</span>`;
                }},
                { data: 'published_at', name: 'published_at', render: function (data) {
                    return data ? moment(data).format('DD/MM/YYYY HH:mm') : '-';
                }},
                { data: 'status', name: 'status', render: function (data) {
                    return data === 'published'
                        ? '<span class="badge bg-success">Published</span>'
                        : '<span class="badge bg-secondary">Draft</span>';
                }},
                { data: null, name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    let buttons = '<div class=\"btn-group btn-group-sm\">' +
                        '<button class=\"btn btn-info\" onclick=\"viewAnnouncement(' + row.id + ')\" title=\"View\"><i class=\"bi bi-eye\"></i></button>';
                    if (isAdmin) {
                        buttons += '<button class=\"btn btn-primary\" onclick=\"openEditModal(' + row.id + ')\" title=\"Edit\"><i class=\"bi bi-pencil\"></i></button>' +
                            '<button class=\"btn btn-danger\" onclick=\"deleteAnnouncement(' + row.id + ')\" title=\"Delete\"><i class=\"bi bi-trash\"></i></button>';
                    }
                    buttons += '</div>';
                    return buttons;
                } }
            ],
            responsive: true,
            order: [[3, 'desc']]
        });
        window.announcementTable = announcementTable;
    });

    function openCreateModal() {
        $.get('{{ route("announcements.create") }}', function (html) {
            $('#globalModalLabel').text('Create Announcement');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function openEditModal(id) {
        $.get('{{ url("announcements") }}/' + id + '/edit', function (html) {
            $('#globalModalLabel').text('Edit Announcement');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function viewAnnouncement(id) {
        $.get('{{ url("announcements") }}/' + id, function (html) {
            $('#globalModalLabel').text('View Announcement');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function deleteAnnouncement(id) {
        $('#confirmDeleteBtn').data('id', id);
        $('#deleteModal').modal('show');
    }

    $(document).on('click', '#confirmDeleteBtn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("announcements") }}/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                $('#deleteModal').modal('hide');
                announcementTable.draw();
                App.toast('Announcement deleted successfully', 'success');
            },
            error: function () {
                App.toast('Error deleting announcement', 'error');
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
