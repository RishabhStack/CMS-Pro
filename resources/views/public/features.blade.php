@extends('layouts.public')

@section('title', 'Features')

@section('content')
<section class="py-5 mt-5">
    <div class="container py-5">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3 rounded-pill">All Features</span>
            <h1 class="display-5 fw-bold mb-3">Complete HR toolkit for modern teams</h1>
            <p class="lead text-muted mx-auto" style="max-width: 600px;">Everything you need to manage people, payroll, and performance in one place.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0">
                <div class="card border-0 shadow-sm h-100 p-3">
                    <div class="card-body">
                        <div class="feature-icon bg-primary bg-opacity-10 text-primary rounded-3 mb-3 d-inline-flex p-3">
                            <i class="bi bi-person-badge fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Employee Directory</h5>
                        <p class="text-muted small">Centralized employee profiles with contact info, documents, skills, and employment history.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm h-100 p-3">
                    <div class="card-body">
                        <div class="feature-icon bg-success bg-opacity-10 text-success rounded-3 mb-3 d-inline-flex p-3">
                            <i class="bi bi-diagram-3 fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Org Chart</h5>
                        <p class="text-muted small">Visual organization hierarchy with reporting structures and department groupings.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-sm h-100 p-3">
                    <div class="card-body">
                        <div class="feature-icon bg-warning bg-opacity-10 text-warning rounded-3 mb-3 d-inline-flex p-3">
                            <i class="bi bi-clock-history fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Time Tracking</h5>
                        <p class="text-muted small">Real-time clock in/out, break tracking, overtime calculation, and attendance reports.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0">
                <div class="card border-0 shadow-sm h-100 p-3">
                    <div class="card-body">
                        <div class="feature-icon bg-info bg-opacity-10 text-info rounded-3 mb-3 d-inline-flex p-3">
                            <i class="bi bi-calendar-check fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Leave Management</h5>
                        <p class="text-muted small">Automated leave policies, balance tracking, approval workflows, and holiday calendars.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm h-100 p-3">
                    <div class="card-body">
                        <div class="feature-icon bg-danger bg-opacity-10 text-danger rounded-3 mb-3 d-inline-flex p-3">
                            <i class="bi bi-wallet2 fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Payroll Processing</h5>
                        <p class="text-muted small">Automated salary calculations, tax deductions, payslip generation, and bank file exports.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-sm h-100 p-3">
                    <div class="card-body">
                        <div class="feature-icon bg-purple bg-opacity-10 text-purple rounded-3 mb-3 d-inline-flex p-3">
                            <i class="bi bi-file-earmark-text fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Document Management</h5>
                        <p class="text-muted small">Secure document storage with versioning, expiry tracking, and employee self-service uploads.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0">
                <div class="card border-0 shadow-sm h-100 p-3">
                    <div class="card-body">
                        <div class="feature-icon bg-primary bg-opacity-10 text-primary rounded-3 mb-3 d-inline-flex p-3">
                            <i class="bi bi-shield-lock fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Role-Based Access</h5>
                        <p class="text-muted small">Granular permissions with custom roles, department-level access, and audit logging.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm h-100 p-3">
                    <div class="card-body">
                        <div class="feature-icon bg-success bg-opacity-10 text-success rounded-3 mb-3 d-inline-flex p-3">
                            <i class="bi bi-graph-up-arrow fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Analytics & Reports</h5>
                        <p class="text-muted small">Custom dashboards, headcount reports, attrition analysis, and exportable data.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-sm h-100 p-3">
                    <div class="card-body">
                        <div class="feature-icon bg-info bg-opacity-10 text-info rounded-3 mb-3 d-inline-flex p-3">
                            <i class="bi bi-megaphone fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Announcements</h5>
                        <p class="text-muted small">Company-wide announcements with priority levels, scheduled publishing, and read receipts.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.text-purple { color: #7c3aed; }
.bg-purple { background-color: #7c3aed; }
.feature-icon { width: 56px; height: 56px; display: inline-flex; align-items: center; justify-content: center; }
</style>
@endpush
