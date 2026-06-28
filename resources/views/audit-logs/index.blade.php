@extends('layouts.master')

@section('title', 'Audit Logs')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0">Audit Logs</h5>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <select id="filterEvent" class="form-select">
                    <option value="">All Events</option>
                    <option value="created">Created</option>
                    <option value="updated">Updated</option>
                    <option value="deleted">Deleted</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="assigned">Assigned</option>
                    <option value="resolved">Resolved</option>
                    <option value="closed">Closed</option>
                    <option value="login">Login</option>
                    <option value="logout">Logout</option>
                </select>
            </div>
        </div>

        <table id="dataTable-audit-logs" class="table table-hover">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Event</th>
                    <th>Module</th>
                    <th>Details</th>
                    <th>Date/Time</th>
                    <th>IP Address</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let auditTable;

    function formatModel(type) {
        if (!type) return '-';
        const parts = type.split('\\');
        const name = parts[parts.length - 1];
        return name.replace(/([A-Z])/g, ' $1').trim();
    }

    function formatEvent(event) {
        const badges = {
            created: 'success',
            updated: 'info',
            deleted: 'danger',
            approved: 'primary',
            rejected: 'warning',
            assigned: 'secondary',
            resolved: 'success',
            closed: 'secondary',
            login: 'info',
            logout: 'secondary',
        };
        return `<span class="badge bg-${badges[event] || 'secondary'}">${event.charAt(0).toUpperCase() + event.slice(1)}</span>`;
    }

    $(document).ready(function () {
        auditTable = $('#dataTable-audit-logs').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("audit-logs.list") }}',
                data: function (d) {
                    d.event = $('#filterEvent').val();
                }
            },
            columns: [
                { data: 'user', name: 'user.first_name', render: function (data) {
                    return data ? data.first_name + ' ' + data.last_name : 'System';
                }},
                { data: 'event', name: 'event', render: function (data) {
                    return formatEvent(data);
                }},
                { data: 'auditable_type', name: 'auditable_type', render: function (data) {
                    return formatModel(data);
                }},
                { data: 'new_values', name: 'new_values', orderable: false, render: function (data, type, row) {
                    if (type === 'display') {
                        let text = '';
                        if (row.event === 'created' && data) {
                            const vals = typeof data === 'string' ? JSON.parse(data) : data;
                            const keys = Object.keys(vals).filter(k => !['id', 'company_id', 'user_id', 'created_by', 'updated_at', 'created_at', 'deleted_at', 'ip_address', 'user_agent', 'password', 'remember_token'].includes(k));
                            text = keys.slice(0, 3).map(k => k.replace(/_/g, ' ') + ': ' + (vals[k] ? (typeof vals[k] === 'string' && vals[k].length > 40 ? vals[k].substring(0, 40) + '...' : vals[k]) : '-')).join(', ');
                        } else if (row.event === 'updated' && row.old_values && data) {
                            const oldV = typeof row.old_values === 'string' ? JSON.parse(row.old_values) : row.old_values;
                            const newV = typeof data === 'string' ? JSON.parse(data) : data;
                            const changes = [];
                            Object.keys(newV).forEach(k => {
                                if (oldV[k] !== newV[k] && !['id', 'company_id', 'user_id', 'updated_at', 'created_at', 'deleted_at'].includes(k)) {
                                    changes.push(k.replace(/_/g, ' ') + ': ' + (oldV[k] || 'empty') + ' → ' + (newV[k] || 'empty'));
                                }
                            });
                            text = changes.slice(0, 2).join('; ');
                        } else if (row.event === 'deleted' && row.old_values) {
                            const vals = typeof row.old_values === 'string' ? JSON.parse(row.old_values) : row.old_values;
                            const keys = Object.keys(vals).filter(k => !['id', 'company_id', 'user_id', 'created_by', 'updated_at', 'created_at', 'deleted_at'].includes(k));
                            text = keys.slice(0, 2).map(k => k.replace(/_/g, ' ') + ': ' + (vals[k] || '-')).join(', ');
                        } else {
                            text = row.event === 'login' ? 'User logged in' : row.event === 'logout' ? 'User logged out' : 'Action performed';
                        }
                        return text || '-';
                    }
                    return data;
                }},
                { data: 'created_at', name: 'created_at', render: function (data) {
                    return data ? moment(data).format('DD/MM/YYYY h:mm A') : '-';
                }},
                { data: 'ip_address', name: 'ip_address', render: function (data) {
                    return data || '-';
                }},
            ],
            responsive: true,
            order: [[4, 'desc']]
        });
        window.auditTable = auditTable;

        $('#filterEvent').change(function () {
            auditTable.draw();
        });
    });
</script>
@endpush
