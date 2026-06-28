@extends('layouts.master')

@section('title', 'Performance Reviews')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0">Performance Reviews</h5>
        @if(auth()->user()->hasRole(['Owner', 'Admin']))
            <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                <i class="bi bi-plus-lg"></i> Add Review
            </button>
        @endif
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <select id="filterStatus" class="form-select">
                    <option value="">All Status</option>
                    <option value="draft">Draft</option>
                    <option value="pending_review">Pending Review</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filterEmployee" class="form-select">
                    <option value="">All Employees</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <table id="dataTable-reviews" class="table table-hover">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Period</th>
                    <th>Due Date</th>
                    <th>Goals</th>
                    <th>Overall Rating</th>
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
    const currentEmployeeId = {{ auth()->user()->employee?->id ?? 'null' }};
    let reviewsTable;

    $(document).ready(function () {
        $('.flatpickr').flatpickr({ dateFormat: 'Y-m-d', allowInput: true });

        reviewsTable = $('#dataTable-reviews').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("performance-reviews.list") }}',
                data: function (d) {
                    d.status = $('#filterStatus').val();
                    d.employee_id = $('#filterEmployee').val();
                }
            },
            columns: [
                { data: 'employee.full_name', name: 'employee.full_name' },
                { data: 'review_period', name: 'review_period' },
                { data: 'due_date', name: 'due_date', render: function (data) {
                    return data ? moment(data).format('DD/MM/YYYY') : '-';
                }},
                { data: 'goals_count', name: 'goals_count', searchable: false },
                { data: 'overall_rating', name: 'overall_rating', render: function (data) {
                    return data !== null ? data : '-';
                }},
                { data: 'status', name: 'status', render: function (data) {
                    const badges = { draft: 'secondary', pending_review: 'warning', completed: 'success', cancelled: 'danger' };
                    return `<span class="badge bg-${badges[data] || 'secondary'}">${ucfirst(data.replace('_', ' '))}</span>`;
                }},
                { data: null, name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    let buttons = '<div class="btn-group btn-group-sm">' +
                        '<button class="btn btn-info" onclick="viewReview(' + row.id + ')" title="View"><i class="bi bi-eye"></i></button>';

                    if (isAdmin) {
                        buttons += '<button class="btn btn-primary" onclick="openEditModal(' + row.id + ')" title="Edit"><i class="bi bi-pencil"></i></button>' +
                            '<button class="btn btn-danger" onclick="deleteReview(' + row.id + ')" title="Delete"><i class="bi bi-trash"></i></button>';
                    }

                    if (row.status === 'draft' && (isAdmin || row.employee_id === currentEmployeeId)) {
                        buttons += '<button class="btn btn-success" onclick="submitReview(' + row.id + ')" title="Submit for Review"><i class="bi bi-send"></i></button>';
                    }

                    if (row.status === 'pending_review' && isAdmin) {
                        buttons += '<button class="btn btn-success" onclick="completeReview(' + row.id + ')" title="Complete Review"><i class="bi bi-check-lg"></i></button>';
                    }

                    buttons += '</div>';
                    return buttons;
                }}
            ],
            responsive: true,
            order: [[1, 'desc']]
        });
        window.reviewsTable = reviewsTable;

        $('#filterStatus, #filterEmployee').change(function () {
            reviewsTable.draw();
        });
    });

    function openCreateModal() {
        $.get('{{ route("performance-reviews.create") }}', function (html) {
            $('#globalModalLabel').text('Create Performance Review');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function openEditModal(id) {
        $.get('{{ url("performance-reviews") }}/' + id + '/edit', function (html) {
            $('#globalModalLabel').text('Edit Performance Review');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function viewReview(id) {
        $.get('{{ url("performance-reviews") }}/' + id, function (html) {
            $('#globalModalLabel').text('View Performance Review');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function submitReview(id) {
        Swal.fire({
            title: 'Submit for Review?',
            text: 'Are you sure you want to submit this review for approval?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("performance-reviews") }}/' + id + '/submit',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function () {
                        reviewsTable.draw();
                        App.toast('Review submitted successfully', 'success');
                    },
                    error: function (xhr) {
                        App.toast(xhr.responseJSON?.message || 'Error submitting review', 'error');
                    }
                });
            }
        });
    }

    function completeReview(id) {
        Swal.fire({
            title: 'Complete Review?',
            text: 'Enter the overall rating for this review.',
            icon: 'question',
            input: 'number',
            inputAttributes: { min: 0, max: 100, step: 0.1 },
            showCancelButton: true,
            confirmButtonText: 'Yes, complete',
            cancelButtonText: 'Cancel',
            preConfirm: (rating) => {
                if (!rating && rating !== 0) {
                    Swal.showValidationMessage('Rating is required');
                }
                return rating;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("performance-reviews") }}/' + id + '/complete',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        overall_rating: result.value
                    },
                    success: function () {
                        reviewsTable.draw();
                        App.toast('Review completed successfully', 'success');
                    },
                    error: function (xhr) {
                        App.toast(xhr.responseJSON?.message || 'Error completing review', 'error');
                    }
                });
            }
        });
    }

    function deleteReview(id) {
        $('#confirmDeleteBtn').data('id', id);
        $('#deleteModal').modal('show');
    }

    $(document).on('click', '#confirmDeleteBtn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("performance-reviews") }}/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                $('#deleteModal').modal('hide');
                reviewsTable.draw();
                App.toast('Review deleted successfully', 'success');
            },
            error: function () {
                App.toast('Error deleting review', 'error');
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
