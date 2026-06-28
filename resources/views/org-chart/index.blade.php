@extends('layouts.master')

@section('title', 'Organization Chart')

@push('styles')
<style>
.org-chart-wrapper {
    overflow-x: auto;
    padding: 1rem 0;
}

.org-columns {
    display: flex;
    gap: 2rem;
    min-width: min-content;
    justify-content: center;
}

.org-department {
    min-width: 220px;
    flex-shrink: 0;
}

.org-department-header {
    background: var(--bs-primary, #3b82f6);
    color: #fff;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    text-align: center;
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
    position: relative;
}

.org-department-header::after {
    content: '';
    position: absolute;
    bottom: -12px;
    left: 50%;
    transform: translateX(-50%);
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-top: 12px solid var(--bs-primary, #3b82f6);
}

.org-employees {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    align-items: center;
    position: relative;
}

.org-employees::before {
    content: '';
    position: absolute;
    top: -8px;
    left: 50%;
    width: 2px;
    height: 8px;
    background: var(--bs-primary, #3b82f6);
}

.org-employee-card {
    background: var(--bs-body-bg, #fff);
    border: 1px solid var(--bs-border-color, #dee2e6);
    border-radius: 10px;
    padding: 0.75rem 1rem;
    width: 100%;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    position: relative;
    text-align: center;
}

.org-employee-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.org-employee-card.manager {
    border-color: var(--bs-primary, #3b82f6);
    background: rgba(59,130,246,0.03);
    border-width: 2px;
}

.org-employee-card.manager::before {
    content: '\F4D6';
    font-family: 'bootstrap-icons';
    position: absolute;
    top: -6px;
    right: -6px;
    background: var(--bs-primary, #3b82f6);
    color: #fff;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 0.65rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.org-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
    margin: 0 auto 0.5rem;
    background: var(--bs-primary-bg-subtle, #e8f0fe);
    color: var(--bs-primary, #3b82f6);
}

.org-employee-card.manager .org-avatar {
    background: var(--bs-primary, #3b82f6);
    color: #fff;
}

.org-employee-name {
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 0.15rem;
    line-height: 1.2;
}

.org-employee-desig {
    font-size: 0.75rem;
    color: var(--bs-secondary-color, #6c757d);
    line-height: 1.2;
}

.org-children {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-top: 0.5rem;
    padding-left: 1rem;
    border-left: 2px solid var(--bs-border-color, #dee2e6);
    width: 100%;
}

.org-child-card {
    background: var(--bs-tertiary-bg, #f8f9fa);
    border: 1px solid var(--bs-border-color, #dee2e6);
    border-radius: 8px;
    padding: 0.5rem 0.75rem;
    cursor: pointer;
    transition: transform 0.2s;
    text-align: left;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.org-child-card:hover {
    transform: translateX(3px);
}

.org-child-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.7rem;
    flex-shrink: 0;
    background: var(--bs-secondary-bg-subtle, #e9ecef);
    color: var(--bs-secondary-color, #6c757d);
}

.org-child-info {
    flex: 1;
    min-width: 0;
}

.org-child-name {
    font-size: 0.8rem;
    font-weight: 600;
    line-height: 1.2;
}

.org-child-desig {
    font-size: 0.7rem;
    color: var(--bs-secondary-color, #6c757d);
}

.loading-spinner {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 400px;
}

.modal-employee-detail {
    text-align: center;
}

.modal-employee-detail .modal-avatar {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.5rem;
    margin: 0 auto 1rem;
    background: var(--bs-primary-bg-subtle, #e8f0fe);
    color: var(--bs-primary, #3b82f6);
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--bs-border-color, #dee2e6);
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    font-size: 0.8rem;
    color: var(--bs-secondary-color, #6c757d);
}

.detail-value {
    font-size: 0.85rem;
    font-weight: 500;
}
</style>
@endpush

@section('content')
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bi bi-diagram-3 me-1"></i> Organization Chart</h5>
            </div>
            <div class="card-body">
                <div id="orgChartContainer" class="org-chart-wrapper">
                    <div class="loading-spinner">
                        <div class="text-center">
                            <div class="spinner-border text-primary mb-2"></div>
                            <p class="text-muted small mb-0">Loading organization chart...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Employee Detail Modal --}}
<div class="modal fade" id="employeeModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-4" id="employeeModalBody"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
async function loadOrgChart() {
    const container = document.getElementById('orgChartContainer');
    try {
        const res = await axios.get('{{ route("orgchart.data") }}');
        const data = res.data;
        container.innerHTML = renderOrgTree(data);
    } catch (e) {
        container.innerHTML = '<div class="text-center py-5"><i class="bi bi-exclamation-triangle fs-1 text-danger"></i><p class="text-muted mt-2">Failed to load organization chart.</p></div>';
    }
}

function renderOrgTree(departments) {
    if (!departments || departments.length === 0) {
        return '<div class="text-center py-5"><p class="text-muted">No employees found.</p></div>';
    }
    let html = '<div class="org-columns">';
    departments.forEach(dept => {
        html += `<div class="org-department">
            <div class="org-department-header">${escapeHtml(dept.name)}</div>
            <div class="org-employees">`;
        dept.children.forEach(mgr => {
            html += renderEmployeeCard(mgr, true);
        });
        html += `</div></div>`;
    });
    html += '</div>';
    return html;
}

function renderEmployeeCard(emp, isManager) {
    const initial = emp.name ? emp.name.charAt(0).toUpperCase() : '?';
    let html = `<div class="org-employee-card ${isManager ? 'manager' : ''}" onclick='showEmployee(${JSON.stringify(emp).replace(/'/g, "&#39;")})'>
        <div class="org-avatar">${initial}</div>
        <div class="org-employee-name">${escapeHtml(emp.name)}</div>
        <div class="org-employee-desig">${escapeHtml(emp.designation)}</div>
    </div>`;
    if (emp.children && emp.children.length > 0) {
        html += '<div class="org-children">';
        emp.children.forEach(child => {
            const cInitial = child.name ? child.name.charAt(0).toUpperCase() : '?';
            html += `<div class="org-child-card" onclick='showEmployee(${JSON.stringify(child).replace(/'/g, "&#39;")})'>
                <div class="org-child-avatar">${cInitial}</div>
                <div class="org-child-info">
                    <div class="org-child-name">${escapeHtml(child.name)}</div>
                    <div class="org-child-desig">${escapeHtml(child.designation)}</div>
                </div>
            </div>`;
        });
        html += '</div>';
    }
    return html;
}

function showEmployee(emp) {
    const initial = emp.name ? emp.name.charAt(0).toUpperCase() : '?';
    const body = document.getElementById('employeeModalBody');
    body.innerHTML = `
        <div class="modal-employee-detail">
            <div class="modal-avatar">${initial}</div>
            <h5 class="mb-3">${escapeHtml(emp.name)}</h5>
            <div class="detail-row"><span class="detail-label">Email</span><span class="detail-value">${escapeHtml(emp.email)}</span></div>
            <div class="detail-row"><span class="detail-label">Department</span><span class="detail-value">${escapeHtml(emp.department)}</span></div>
            <div class="detail-row"><span class="detail-label">Designation</span><span class="detail-value">${escapeHtml(emp.designation)}</span></div>
            <div class="detail-row"><span class="detail-label">Joined</span><span class="detail-value">${escapeHtml(emp.joining_date)}</span></div>
        </div>`;
    const modal = new bootstrap.Modal(document.getElementById('employeeModal'));
    modal.show();
}

function escapeHtml(str) {
    if (!str) return '';
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

loadOrgChart();
</script>
@endpush
