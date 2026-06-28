@extends('layouts.master')

@section('title', 'Calendar')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/main.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/main.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.15/main.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/list@6.1.15/main.min.css" rel="stylesheet">
<style>
    #calendar { max-width: 100%; }
    .fc { font-size: 0.85rem; }
    .fc .fc-toolbar-title { font-size: 1.2rem; font-weight: 600; }
    .fc .fc-button-primary {
        background-color: var(--bs-primary, #4361ee);
        border-color: var(--bs-primary, #4361ee);
    }
    .fc .fc-button-primary:not(:disabled).fc-button-active,
    .fc .fc-button-primary:not(:disabled):active {
        background-color: var(--bs-primary-darker, #3651d4);
        border-color: var(--bs-primary-darker, #3651d4);
    }
    .fc .fc-day-today { background-color: rgba(67, 97, 238, 0.05) !important; }
    .fc .fc-list-event:hover td { background-color: rgba(67, 97, 238, 0.03); }
    .fc .fc-list-event-title a { color: inherit; }
    .legend { display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; }
    .legend-item { display: flex; align-items: center; gap: 0.35rem; font-size: 0.8rem; }
    .legend-dot { width: 12px; height: 12px; border-radius: 3px; }
    .summary-card {
        cursor: pointer; transition: transform 0.1s;
    }
    .summary-card:hover { transform: translateY(-2px); }
    .fc .fc-event { cursor: pointer; }
    .fc .fc-list-empty { padding: 2rem; color: var(--bs-secondary); }
    #tableToggle { cursor: pointer; }
    .table-view .fc { display: none; }
    .table-view #eventsTableWrapper { display: block !important; }
    #eventsTableWrapper { display: none; }
    .view-switch .btn.active { z-index: 2; }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0"><i class="bi bi-calendar3 me-1"></i> Company Calendar</h5>
        <div class="d-flex gap-2 align-items-center">
            <div class="legend">
                <span class="legend-item"><span class="legend-dot" style="background:#10b981"></span> Annual Leave</span>
                <span class="legend-item"><span class="legend-dot" style="background:#3b82f6"></span> Sick Leave</span>
                <span class="legend-item"><span class="legend-dot" style="background:#8b5cf6"></span> Personal Leave</span>
                <span class="legend-item"><span class="legend-dot" style="background:#f59e0b"></span> Holiday</span>
            </div>
            <div class="btn-group btn-group-sm view-switch" role="group">
                <button type="button" class="btn btn-outline-primary active" id="calendarViewBtn" title="Calendar View">
                    <i class="bi bi-calendar3"></i>
                </button>
                <button type="button" class="btn btn-outline-primary" id="listViewBtn" title="List View">
                    <i class="bi bi-list-ul"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="calendar"></div>
        <div id="eventsTableWrapper">
            <table id="eventsTable" class="table table-hover table-sm">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalTitle">Event Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="eventModalBody"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/list@6.1.15/index.global.min.js"></script>
<script>
    const isAdmin = @json(auth()->user()->hasRole(['Owner', 'Admin']));
    let calendar;
    let allEvents = [];

    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next,today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            buttonText: {
                today: 'Today',
                month: 'Month',
                week: 'Week',
                list: 'List'
            },
            height: 'auto',
            firstDay: 1,
            navLinks: true,
            eventSources: [{
                url: '{{ route("calendar.events") }}',
                method: 'GET',
                extraParams: function () {
                    return {};
                },
                failure: function () {
                    App.toast('Failed to load calendar events', 'error');
                }
            }],
            eventDidMount: function (info) {
                if (info.event.extendedProps.type === 'attendance_summary') {
                    const el = info.el;
                    const props = info.event.extendedProps;
                    el.setAttribute('title', 'Present: ' + props.present + ' | Late: ' + props.late + ' | Half-day: ' + props.half_day + ' | Absent: ' + props.absent + ' | Not Marked: ' + props.not_marked);
                    el.style.cursor = 'default';
                }
            },
            eventClick: function (info) {
                if (info.event.extendedProps.type === 'attendance_summary') return;
                const props = info.event.extendedProps;
                let html = '';
                if (props.type === 'holiday') {
                    html = `
                        <p><strong>${info.event.title}</strong></p>
                        <p><span class="badge bg-warning">Holiday</span></p>
                        <p class="mb-0 text-muted">${props.description || 'Public Holiday'}</p>
                    `;
                } else if (props.type === 'leave') {
                    html = `
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="avatar-circle bg-primary text-white" style="width:36px;height:36px;font-size:0.9rem">
                                <span>${props.employee_name ? props.employee_name.charAt(0) : '?'}</span>
                            </div>
                            <div>
                                <strong>${props.employee_name || 'Employee'}</strong>
                                <br><small class="text-muted">${props.leave_type || 'Leave'}</small>
                            </div>
                        </div>
                        <hr class="my-2">
                        <p><strong>Duration:</strong> ${moment(info.event.start).format('MMM D, YYYY')} - ${moment(info.event.end).subtract(1, 'day').format('MMM D, YYYY')}</p>
                        <p><strong>Total Days:</strong> ${props.total_days || 1}</p>
                        ${props.reason ? `<p><strong>Reason:</strong> ${props.reason}</p>` : ''}
                        <p><span class="badge bg-success">Approved</span></p>
                    `;
                }
                document.getElementById('eventModalTitle').textContent = info.event.title;
                document.getElementById('eventModalBody').innerHTML = html;
                new bootstrap.Modal(document.getElementById('eventModal')).show();
            },
            loading: function (isLoading) {
                if (!isLoading) {
                    allEvents = calendar.getEvents().map(e => ({
                        title: e.title,
                        start: e.start ? moment(e.start).format('YYYY-MM-DD') : '',
                        end: e.end ? moment(e.end).subtract(1, 'day').format('YYYY-MM-DD') : '',
                        type: e.extendedProps.type || 'event',
                        typeLabel: e.extendedProps.type === 'holiday' ? 'Holiday' : (e.extendedProps.type === 'leave' ? 'Leave' : 'Attendance'),
                        employee_name: e.extendedProps.employee_name || '',
                        leave_type: e.extendedProps.leave_type || '',
                        reason: e.extendedProps.reason || ''
                    }));
                    populateTable();
                }
            }
        });

        calendar.render();
    });

    $('#calendarViewBtn').click(function () {
        $('#calendar').show();
        $('#eventsTableWrapper').hide();
        $('#calendarViewBtn').addClass('active');
        $('#listViewBtn').removeClass('active');
    });

    $('#listViewBtn').click(function () {
        $('#calendar').hide();
        $('#eventsTableWrapper').show();
        $('#listViewBtn').addClass('active');
        $('#calendarViewBtn').removeClass('active');
    });

    function populateTable() {
        const tbody = $('#eventsTable tbody');
        tbody.empty();
        allEvents.forEach(function (e) {
            if (e.type === 'attendance_summary') return;
            const badgeClass = e.type === 'holiday' ? 'bg-warning' : 'bg-success';
            const details = e.type === 'holiday' ? '' : (e.employee_name + (e.leave_type ? ' - ' + e.leave_type : ''));
            tbody.append(`
                <tr>
                    <td>${e.start}${e.end !== e.start ? ' to ' + e.end : ''}</td>
                    <td>${e.title}</td>
                    <td><span class="badge ${badgeClass}">${e.typeLabel}</span></td>
                    <td>${details}</td>
                </tr>
            `);
        });
    }
</script>
@endpush