@extends('layouts.master')

@section('title', 'Attendance')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0">Daily Attendance</h5>
        @if(auth()->user()->hasRole(['Owner', 'Admin']))
            <button type="button" class="btn btn-primary" onclick="markAttendance()">
                <i class="bi bi-plus-lg"></i> Mark Attendance
            </button>
        @endif
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <input type="text" id="filterDate" class="form-control flatpickr" placeholder="Select date">
            </div>
            <div class="col-md-3">
                <select id="filterDepartment" class="form-select">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select id="filterStatus" class="form-select">
                    <option value="">All Status</option>
                    <option value="present">Present</option>
                    <option value="absent">Absent</option>
                    <option value="late">Late</option>
                    <option value="half-day">Half Day</option>
                    <option value="leave">On Leave</option>
                </select>
            </div>
            @if(auth()->user()->hasRole(['Owner', 'Admin']))
                <div class="col-md-3">
                    <button type="button" class="btn btn-outline-primary w-100" onclick="clockIn()">
                        <i class="bi bi-box-arrow-in-right"></i> Clock In
                    </button>
                </div>
            @endif
        </div>

        <table id="dataTable-attendance" class="table table-hover">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Date</th>
                    <th>Clock In</th>
                    <th>Clock Out</th>
                    <th>Total Hours</th>
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
    let attendanceTable;

    $(document).ready(function () {
        flatpickr('.flatpickr', {
            dateFormat: 'Y-m-d',
            allowInput: true
        });

        attendanceTable = $('#dataTable-attendance').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("attendance.list") }}',
                data: function (d) {
                    d.date = $('#filterDate').val();
                    d.department_id = $('#filterDepartment').val();
                    d.status = $('#filterStatus').val();
                }
            },
            columns: [
                { data: 'employee.full_name', name: 'employee.full_name' },
                { data: 'date', name: 'date', render: function (data) {
                    return data ? moment(data).format('DD/MM/YYYY') : '-';
                }},
                { data: 'clock_in', name: 'clock_in', render: function (data) {
                    return data ? moment(data).format('DD/MM/YYYY HH:mm') : '-';
                }},
                { data: 'clock_out', name: 'clock_out', render: function (data) {
                    return data ? moment(data).format('DD/MM/YYYY HH:mm') : '-';
                }},
                { data: 'total_hours', name: 'total_hours', defaultContent: '-', searchable: false },
                { data: 'status', name: 'status', render: function (data) {
                    const badges = { present: 'success', absent: 'danger', late: 'warning', 'half-day': 'info', leave: 'secondary' };
                    return `<span class="badge bg-${badges[data] || 'secondary'}">${ucfirst(data)}</span>`;
                }},
                { data: null, name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    let buttons = '<div class=\"btn-group btn-group-sm\">';
                    if (isAdmin) {
                        buttons += '<button class=\"btn btn-primary\" onclick=\"editAttendance(' + row.id + ')\" title=\"Edit\"><i class=\"bi bi-pencil\"></i></button>' +
                            '<button class=\"btn btn-danger\" onclick=\"deleteAttendance(' + row.id + ')\" title=\"Delete\"><i class=\"bi bi-trash\"></i></button>';
                    }
                    buttons += '</div>';
                    return buttons;
                } }
            ],
            responsive: true,
            order: [[1, 'desc']]
        });
        window.attendanceTable = attendanceTable;

        $('#filterDate, #filterDepartment, #filterStatus').change(function () {
            attendanceTable.draw();
        });
    });

    function markAttendance() {
        $.get('{{ route("attendance.create") }}', function (html) {
            $('#globalModalLabel').text('Mark Attendance');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function clockIn() {
        $.ajax({
            url: '{{ route("attendance.clock-in") }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function (response) {
                App.toast(response.message || 'Clocked in successfully', 'success');
                attendanceTable.draw();
            },
            error: function (xhr) {
                App.toast(xhr.responseJSON?.message || 'Error clocking in', 'error');
            }
        });
    }

    function clockOut() {
        $.ajax({
            url: '{{ route("attendance.clock-out") }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function (response) {
                App.toast(response.message || 'Clocked out successfully', 'success');
                attendanceTable.draw();
            },
            error: function (xhr) {
                App.toast(xhr.responseJSON?.message || 'Error clocking out', 'error');
            }
        });
    }

    function editAttendance(id) {
        $.get('{{ url("attendance") }}/' + id + '/edit', function (html) {
            $('#globalModalLabel').text('Edit Attendance');
            $('#globalModalBody').html(html);
            $('#globalModal').modal('show');
        });
    }

    function deleteAttendance(id) {
        $('#confirmDeleteBtn').data('id', id);
        $('#deleteModal').modal('show');
    }

    $(document).on('click', '#confirmDeleteBtn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("attendance") }}/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                $('#deleteModal').modal('hide');
                attendanceTable.draw();
                App.toast('Attendance record deleted successfully', 'success');
            },
            error: function () {
                App.toast('Error deleting attendance record', 'error');
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
