@extends('layouts.master')

@section('title', 'Documents')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Document Management</h5>
        <button type="button" class="btn btn-primary" onclick="openUploadModal()">
            <i class="bi bi-plus-lg"></i> Upload Document
        </button>
    </div>
    <div class="card-body">
        <table id="dataTable-documents" class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Employee</th>
                    <th>Size</th>
                    <th>Uploaded Date</th>
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
    let documentTable;

    $(document).ready(function () {
        documentTable = $('#dataTable-documents').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("documents.list") }}',
            columns: [
                { data: 'name', name: 'name' },
                { data: 'type', name: 'type', render: function (data) {
                    const icons = {
                        pdf: '<span class="badge bg-danger"><i class="bi bi-file-pdf"></i> PDF</span>',
                        doc: '<span class="badge bg-primary"><i class="bi bi-file-word"></i> DOC</span>',
                        docx: '<span class="badge bg-primary"><i class="bi bi-file-word"></i> DOCX</span>',
                        xls: '<span class="badge bg-success"><i class="bi bi-file-excel"></i> XLS</span>',
                        xlsx: '<span class="badge bg-success"><i class="bi bi-file-excel"></i> XLSX</span>',
                        jpg: '<span class="badge bg-info"><i class="bi bi-file-image"></i> JPG</span>',
                        jpeg: '<span class="badge bg-info"><i class="bi bi-file-image"></i> JPEG</span>',
                        png: '<span class="badge bg-info"><i class="bi bi-file-image"></i> PNG</span>',
                    };
                    return icons[data?.toLowerCase()] || `<span class="badge bg-secondary"><i class="bi bi-file"></i> ${data?.toUpperCase()}</span>`;
                }},
                { data: 'employee.full_name', name: 'employee.full_name', defaultContent: '-' },
                { data: 'file_size', name: 'file_size', render: function (data) {
                    if (!data) return '-';
                    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(data) / Math.log(1024));
                    return (data / Math.pow(1024, i)).toFixed(2) + ' ' + sizes[i];
                }},
                { data: 'created_at', name: 'created_at', render: function (data) {
                    return data ? moment(data).format('DD/MM/YYYY') : '-';
                }},
                { data: null, name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    let buttons = '<div class=\"btn-group btn-group-sm\">' +
                        '<button class=\"btn btn-success\" onclick=\"downloadDocument(' + row.id + ')\" title=\"Download\"><i class=\"bi bi-download\"></i></button>';
                    if (isAdmin) {
                        buttons += '<button class=\"btn btn-danger\" onclick=\"deleteDocument(' + row.id + ')\" title=\"Delete\"><i class=\"bi bi-trash\"></i></button>';
                    }
                    buttons += '</div>';
                    return buttons;
                } }
            ],
            responsive: true,
            order: [[4, 'desc']]
        });
        window.documentTable = documentTable;
    });

    function openUploadModal() {
        $.get('{{ route("documents.create") }}', function (html) {
            $('#globalModalLabel').text('Upload Document');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function downloadDocument(id) {
        window.location.href = '{{ url("documents") }}/' + id + '/download';
    }

    function deleteDocument(id) {
        $('#confirmDeleteBtn').data('id', id);
        $('#deleteModal').modal('show');
    }

    $(document).on('click', '#confirmDeleteBtn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("documents") }}/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                $('#deleteModal').modal('hide');
                documentTable.draw();
                App.toast('Document deleted successfully', 'success');
            },
            error: function () {
                App.toast('Error deleting document', 'error');
            }
        });
    });
</script>
@endpush
