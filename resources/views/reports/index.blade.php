@extends('layouts.master')

@section('title', 'Reports & Analytics')

@push('styles')
<style>
.report-tab-content { min-height: 400px; }
.chart-container { position: relative; height: 300px; width: 100%; }
.summary-stats .stat-item { padding: 0.75rem; border-radius: 8px; background: var(--bs-tertiary-bg, #f8f9fa); }
.spinner-overlay { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.7); z-index: 5; border-radius: 8px; }
.card { position: relative; }
</style>
@endpush

@section('content')
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="card-title mb-0">Reports & Analytics</h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#saveReportModal">
                        <i class="bi bi-bookmark-plus"></i> Save Report
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#savedReportsModal">
                        <i class="bi bi-bookmark"></i> Saved Reports
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs nav-justified mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#attendance-tab" type="button" role="tab" data-report="attendance">
                            <i class="bi bi-clock me-1"></i> Attendance
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#leave-tab" type="button" role="tab" data-report="leave">
                            <i class="bi bi-calendar-check me-1"></i> Leave
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#payroll-tab" type="button" role="tab" data-report="payroll">
                            <i class="bi bi-wallet2 me-1"></i> Payroll
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#headcount-tab" type="button" role="tab" data-report="headcount">
                            <i class="bi bi-people me-1"></i> Headcount
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#turnover-tab" type="button" role="tab" data-report="turnover">
                            <i class="bi bi-graph-up me-1"></i> Turnover
                        </button>
                    </li>
                </ul>

                <div class="tab-content report-tab-content">
                    <div class="tab-pane fade show active" id="attendance-tab" role="tabpanel">
                        <div class="chart-container">
                            <div class="spinner-overlay d-none"><div class="spinner-border text-primary"></div></div>
                            <canvas id="attendanceChart"></canvas>
                        </div>
                        <div class="summary-stats row g-2 mt-3" id="attendanceStats"></div>
                    </div>
                    <div class="tab-pane fade" id="leave-tab" role="tabpanel">
                        <div class="chart-container">
                            <div class="spinner-overlay d-none"><div class="spinner-border text-primary"></div></div>
                            <canvas id="leaveChart"></canvas>
                        </div>
                        <div class="summary-stats row g-2 mt-3" id="leaveStats"></div>
                    </div>
                    <div class="tab-pane fade" id="payroll-tab" role="tabpanel">
                        <div class="chart-container">
                            <div class="spinner-overlay d-none"><div class="spinner-border text-primary"></div></div>
                            <canvas id="payrollChart"></canvas>
                        </div>
                        <div class="summary-stats row g-2 mt-3" id="payrollStats"></div>
                    </div>
                    <div class="tab-pane fade" id="headcount-tab" role="tabpanel">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="chart-container">
                                    <div class="spinner-overlay d-none"><div class="spinner-border text-primary"></div></div>
                                    <canvas id="headcountChart"></canvas>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="chart-container" style="height:250px;">
                                    <canvas id="employmentTypeChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="summary-stats row g-2 mt-3" id="headcountStats"></div>
                    </div>
                    <div class="tab-pane fade" id="turnover-tab" role="tabpanel">
                        <div class="chart-container">
                            <div class="spinner-overlay d-none"><div class="spinner-border text-primary"></div></div>
                            <canvas id="turnoverChart"></canvas>
                        </div>
                        <div class="summary-stats row g-2 mt-3" id="turnoverStats"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Save Report Modal --}}
<div class="modal fade" id="saveReportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Save Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="saveReportForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Report Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select" id="saveReportType">
                            <option value="attendance">Attendance</option>
                            <option value="leave">Leave</option>
                            <option value="payroll">Payroll</option>
                            <option value="headcount">Headcount</option>
                            <option value="turnover">Turnover</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Saved Reports Modal --}}
<div class="modal fade" id="savedReportsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Saved Reports</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="savedReportsList">
                <p class="text-muted mb-0">Loading...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
let charts = {};

const colorMap = {
    attendance: { present: '#10b981', absent: '#ef4444', late: '#f59e0b' },
    leave: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6', '#f97316'],
    payroll: { earnings: '#10b981', deductions: '#ef4444', net: '#3b82f6' },
    headcount: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6', '#f97316'],
    turnover: { hires: '#10b981', terminations: '#ef4444' },
};

function destroyChart(key) {
    if (charts[key]) { charts[key].destroy(); delete charts[key]; }
}

function showSpinner(tabId) {
    document.querySelector(`#${tabId} .spinner-overlay`)?.classList.remove('d-none');
}

function hideSpinner(tabId) {
    document.querySelector(`#${tabId} .spinner-overlay`)?.classList.add('d-none');
}

async function loadAttendance() {
    const tab = 'attendance-tab';
    showSpinner(tab);
    try {
        const res = await axios.get('{{ route("reports.attendance-trend") }}');
        const data = res.data;
        destroyChart('attendance');
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        charts.attendance = new Chart(ctx, {
            type: 'line',
            data: { labels: data.labels, datasets: data.datasets },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } },
                scales: { y: { beginAtZero: true } }
            }
        });
        const total = data.datasets[0].data.reduce((a,b) => a + b, 0);
        const avg = Math.round(total / data.datasets[0].data.length);
        document.getElementById('attendanceStats').innerHTML = `
            <div class="col-md-4"><div class="stat-item text-center"><small class="text-muted d-block">Avg Present</small><strong>${avg}</strong></div></div>
            <div class="col-md-4"><div class="stat-item text-center"><small class="text-muted d-block">Total Absences</small><strong>${data.datasets[1].data.reduce((a,b) => a + b, 0)}</strong></div></div>
            <div class="col-md-4"><div class="stat-item text-center"><small class="text-muted d-block">Total Late</small><strong>${data.datasets[2].data.reduce((a,b) => a + b, 0)}</strong></div></div>`;
    } catch (e) { App.toast('Failed to load attendance data', 'error'); }
    hideSpinner(tab);
}

async function loadLeave() {
    const tab = 'leave-tab';
    showSpinner(tab);
    try {
        const res = await axios.get('{{ route("reports.leave-trend") }}');
        const data = res.data;
        destroyChart('leave');
        const ctx = document.getElementById('leaveChart').getContext('2d');
        charts.leave = new Chart(ctx, {
            type: 'bar',
            data: { labels: data.labels, datasets: data.datasets },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } },
                scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true } }
            }
        });
        const totals = data.datasets.map(d => d.data.reduce((a,b) => a + b, 0));
        const totalDays = totals.reduce((a,b) => a + b, 0);
        document.getElementById('leaveStats').innerHTML = `
            <div class="col-md-4"><div class="stat-item text-center"><small class="text-muted d-block">Total Leave Days</small><strong>${totalDays}</strong></div></div>
            <div class="col-md-4"><div class="stat-item text-center"><small class="text-muted d-block">Leave Types</small><strong>${data.datasets.length}</strong></div></div>
            <div class="col-md-4"><div class="stat-item text-center"><small class="text-muted d-block">Most Used</small><strong>${data.datasets[totals.indexOf(Math.max(...totals))]?.label || '-'}</strong></div></div>`;
    } catch (e) { App.toast('Failed to load leave data', 'error'); }
    hideSpinner(tab);
}

async function loadPayroll() {
    const tab = 'payroll-tab';
    showSpinner(tab);
    try {
        const res = await axios.get('{{ route("reports.payroll-summary") }}');
        const data = res.data;
        destroyChart('payroll');
        const ctx = document.getElementById('payrollChart').getContext('2d');
        charts.payroll = new Chart(ctx, {
            type: 'bar',
            data: { labels: data.labels, datasets: data.datasets },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } },
                scales: { y: { beginAtZero: true } }
            }
        });
        const totalEarnings = data.datasets[0].data.reduce((a,b) => a + b, 0);
        const totalDeductions = data.datasets[1].data.reduce((a,b) => a + b, 0);
        const totalNet = data.datasets[2].data.reduce((a,b) => a + b, 0);
        document.getElementById('payrollStats').innerHTML = `
            <div class="col-md-4"><div class="stat-item text-center"><small class="text-muted d-block">Total Earnings</small><strong>{{ $company->currency ?? '$' }}${totalEarnings.toLocaleString()}</strong></div></div>
            <div class="col-md-4"><div class="stat-item text-center"><small class="text-muted d-block">Total Deductions</small><strong>{{ $company->currency ?? '$' }}${totalDeductions.toLocaleString()}</strong></div></div>
            <div class="col-md-4"><div class="stat-item text-center"><small class="text-muted d-block">Total Net Pay</small><strong>{{ $company->currency ?? '$' }}${totalNet.toLocaleString()}</strong></div></div>`;
    } catch (e) { App.toast('Failed to load payroll data', 'error'); }
    hideSpinner(tab);
}

async function loadHeadcount() {
    const tab = 'headcount-tab';
    showSpinner(tab);
    try {
        const res = await axios.get('{{ route("reports.headcount") }}');
        const data = res.data;
        destroyChart('headcount');
        destroyChart('employmentType');
        const ctx = document.getElementById('headcountChart').getContext('2d');
        charts.headcount = new Chart(ctx, {
            type: 'bar',
            data: { labels: data.departments.labels, datasets: data.departments.datasets },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
        const ctx2 = document.getElementById('employmentTypeChart').getContext('2d');
        charts.employmentType = new Chart(ctx2, {
            type: 'doughnut',
            data: { labels: data.employmentTypes.labels, datasets: data.employmentTypes.datasets },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 8 } } }
            }
        });
        const total = data.departments.datasets[0].data.reduce((a,b) => a + b, 0);
        const deptCount = data.departments.labels.length;
        document.getElementById('headcountStats').innerHTML = `
            <div class="col-md-4"><div class="stat-item text-center"><small class="text-muted d-block">Total Employees</small><strong>${total}</strong></div></div>
            <div class="col-md-4"><div class="stat-item text-center"><small class="text-muted d-block">Departments</small><strong>${deptCount}</strong></div></div>
            <div class="col-md-4"><div class="stat-item text-center"><small class="text-muted d-block">Employment Types</small><strong>${data.employmentTypes.labels.length}</strong></div></div>`;
    } catch (e) { App.toast('Failed to load headcount data', 'error'); }
    hideSpinner(tab);
}

async function loadTurnover() {
    const tab = 'turnover-tab';
    showSpinner(tab);
    try {
        const res = await axios.get('{{ route("reports.turnover-rate") }}');
        const data = res.data;
        destroyChart('turnover');
        const ctx = document.getElementById('turnoverChart').getContext('2d');
        charts.turnover = new Chart(ctx, {
            type: 'line',
            data: { labels: data.labels, datasets: data.datasets },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
        const totalHires = data.datasets[0].data.reduce((a,b) => a + b, 0);
        const totalTerms = data.datasets[1].data.reduce((a,b) => a + b, 0);
        document.getElementById('turnoverStats').innerHTML = `
            <div class="col-md-4"><div class="stat-item text-center"><small class="text-muted d-block">Total Hires</small><strong>${totalHires}</strong></div></div>
            <div class="col-md-4"><div class="stat-item text-center"><small class="text-muted d-block">Total Terminations</small><strong>${totalTerms}</strong></div></div>
            <div class="col-md-4"><div class="stat-item text-center"><small class="text-muted d-block">Net Growth</small><strong>${totalHires - totalTerms}</strong></div></div>`;
    } catch (e) { App.toast('Failed to load turnover data', 'error'); }
    hideSpinner(tab);
}

document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
    tab.addEventListener('shown.bs.tab', function () {
        const report = this.dataset.report;
        if (report === 'attendance') loadAttendance();
        else if (report === 'leave') loadLeave();
        else if (report === 'payroll') loadPayroll();
        else if (report === 'headcount') loadHeadcount();
        else if (report === 'turnover') loadTurnover();
    });
});

document.getElementById('saveReportForm').addEventListener('submit', async function (e) {
    e.preventDefault();
    const form = this;
    const data = new FormData(form);
    try {
        const res = await axios.post('{{ route("reports.save") }}', Object.fromEntries(data));
        App.toast(res.data.message || 'Report saved', 'success');
        bootstrap.Modal.getInstance(document.getElementById('saveReportModal')).hide();
        form.reset();
    } catch (err) {
        App.toast('Failed to save report', 'error');
    }
});

document.getElementById('savedReportsModal').addEventListener('show.bs.modal', async function () {
    const list = document.getElementById('savedReportsList');
    list.innerHTML = '<p class="text-muted mb-0">Loading...</p>';
    try {
        const res = await axios.get('{{ route("reports.saved") }}');
        const reports = res.data;
        if (reports.length === 0) {
            list.innerHTML = '<p class="text-muted mb-0">No saved reports yet.</p>';
            return;
        }
        list.innerHTML = reports.map(r => `
            <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                <div>
                    <strong>${r.name}</strong>
                    <br><small class="text-muted">${r.type} &middot; ${r.created_by} &middot; ${r.created_at}</small>
                </div>
                <span class="badge bg-primary">${r.type}</span>
            </div>
        `).join('');
    } catch (e) {
        list.innerHTML = '<p class="text-muted mb-0">Failed to load saved reports.</p>';
    }
});

loadAttendance();
</script>
@endpush
