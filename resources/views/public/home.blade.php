@extends('layouts.public')

@section('title', config('app.name'))

@section('content')

<section class="hero-section bg-gradient-primary text-white pt-5">
    <div class="container pt-5">
        <div class="row align-items-center min-vh-80 pt-5">
            <div class="col-lg-6" data-aos="fade-right">
                <span class="badge bg-white text-primary px-3 py-2 mb-3 fs-6 rounded-pill">
                    <i class="bi bi-rocket-takeoff me-1"></i> Trusted by 10,000+ companies
                </span>
                <h1 class="display-4 fw-bold mb-3 lh-1">Enterprise HRMS<br>Made <span class="text-warning">Simple</span></h1>
                <p class="lead mb-4 text-white-80">Manage your entire workforce from one powerful platform. From hiring to payroll, attendance to performance — we've got you covered.</p>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="{{ route('register') }}" class="btn btn-warning btn-lg px-5 fw-semibold">
                        Start Free Trial <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    <a href="{{ route('features') }}" class="btn btn-outline-light btn-lg px-5">
                        <i class="bi bi-play-circle me-2"></i>See Features
                    </a>
                </div>
                <div class="d-flex gap-4 text-white-80 small">
                    <span><i class="bi bi-check-circle-fill text-warning me-1"></i> No credit card</span>
                    <span><i class="bi bi-check-circle-fill text-warning me-1"></i> 14-day free trial</span>
                    <span><i class="bi bi-check-circle-fill text-warning me-1"></i> Cancel anytime</span>
                </div>
            </div>
            <div class="col-lg-6 mt-5 mt-lg-0" data-aos="fade-left">
                <div class="position-relative">
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                        <div class="card-body p-0">
                            <img src="https://placehold.co/600x400/4f46e5/ffffff?text=HRMS+Dashboard" alt="HRMS Dashboard" class="img-fluid">
                        </div>
                    </div>
                    <div class="position-absolute bottom-0 start-0 translate-middle-y ms-n4">
                        <div class="card bg-success text-white border-0 shadow">
                            <div class="card-body py-2 px-3">
                                <small><i class="bi bi-check-circle me-1"></i> 98% satisfaction rate</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 border-bottom bg-light">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col text-center">
                <p class="text-muted mb-3 fw-semibold small text-uppercase tracking-wider">Trusted by industry leaders</p>
                <div class="d-flex flex-wrap justify-content-center gap-5 align-items-center opacity-50">
                    <span class="fs-4 fw-bold text-secondary">TechCorp</span>
                    <span class="fs-4 fw-bold text-secondary">InnovateAI</span>
                    <span class="fs-4 fw-bold text-secondary">CloudBase</span>
                    <span class="fs-4 fw-bold text-secondary">DataFlow</span>
                    <span class="fs-4 fw-bold text-secondary">NexGen</span>
                    <span class="fs-4 fw-bold text-secondary">SmartHire</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5" id="features">
    <div class="container py-5">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3 rounded-pill">Platform Features</span>
            <h2 class="display-6 fw-bold mb-3">Everything you need to manage your workforce</h2>
            <p class="lead text-muted mx-auto" style="max-width: 600px;">Powerful tools to handle every aspect of HR management, from recruitment to retirement.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm h-100 hover-shadow">
                    <div class="card-body p-4">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-3 mb-3 d-inline-flex p-3">
                            <i class="bi bi-people fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Employee Management</h5>
                        <p class="text-muted small">Complete employee lifecycle management with digital profiles, documents, and self-service portals.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-sm h-100 hover-shadow">
                    <div class="card-body p-4">
                        <div class="icon-box bg-success bg-opacity-10 text-success rounded-3 mb-3 d-inline-flex p-3">
                            <i class="bi bi-clock fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Time & Attendance</h5>
                        <p class="text-muted small">Real-time tracking with clock in/out, break management, overtime calculation, and detailed reports.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card border-0 shadow-sm h-100 hover-shadow">
                    <div class="card-body p-4">
                        <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-3 mb-3 d-inline-flex p-3">
                            <i class="bi bi-calendar-check fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Leave Management</h5>
                        <p class="text-muted small">Streamlined leave requests, approvals, balance tracking, and holiday calendar integration.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm h-100 hover-shadow">
                    <div class="card-body p-4">
                        <div class="icon-box bg-info bg-opacity-10 text-info rounded-3 mb-3 d-inline-flex p-3">
                            <i class="bi bi-wallet2 fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Payroll Management</h5>
                        <p class="text-muted small">Automated payroll processing with tax calculations, deductions, payslips, and compliance reporting.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-sm h-100 hover-shadow">
                    <div class="card-body p-4">
                        <div class="icon-box bg-danger bg-opacity-10 text-danger rounded-3 mb-3 d-inline-flex p-3">
                            <i class="bi bi-bar-chart fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Reports & Analytics</h5>
                        <p class="text-muted small">Comprehensive dashboards and custom reports with real-time insights into your workforce metrics.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card border-0 shadow-sm h-100 hover-shadow">
                    <div class="card-body p-4">
                        <div class="icon-box bg-purple bg-opacity-10 text-purple rounded-3 mb-3 d-inline-flex p-3">
                            <i class="bi bi-shield-check fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Role-Based Access</h5>
                        <p class="text-muted small">Granular permission controls with custom roles, ensuring the right people have the right access.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-primary text-white" data-aos="fade-up">
    <div class="container py-5">
        <div class="row text-center g-4">
            <div class="col-6 col-lg-3">
                <div class="counter-item">
                    <div class="display-4 fw-bold mb-1">500+</div>
                    <p class="text-white-80 mb-0">HR Templates</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="counter-item">
                    <div class="display-4 fw-bold mb-1">50K+</div>
                    <p class="text-white-80 mb-0">Active Users</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="counter-item">
                    <div class="display-4 fw-bold mb-1">99.9%</div>
                    <p class="text-white-80 mb-0">Uptime SLA</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="counter-item">
                    <div class="display-4 fw-bold mb-1">150+</div>
                    <p class="text-white-80 mb-0">Countries</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container py-5">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3 rounded-pill">Testimonials</span>
            <h2 class="display-6 fw-bold mb-3">Loved by HR teams worldwide</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="mb-3 text-warning">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <p class="mb-3">"This HRMS transformed our entire HR operations. The payroll automation alone saved us 15 hours every month."</p>
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-circle bg-primary text-white">SK</div>
                            <div>
                                <h6 class="fw-bold mb-0">Sarah Kim</h6>
                                <small class="text-muted">HR Director, TechCorp</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="mb-3 text-warning">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <p class="mb-3">"The leave management and attendance tracking are incredibly intuitive. Our employees love the self-service portal."</p>
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-circle bg-success text-white">MR</div>
                            <div>
                                <h6 class="fw-bold mb-0">Mike Rodriguez</h6>
                                <small class="text-muted">CEO, CloudBase Inc.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="mb-3 text-warning">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <p class="mb-3">"Implementation was smooth and the support team was exceptional. Highly recommended for growing companies."</p>
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-circle bg-warning text-white">AL</div>
                            <div>
                                <h6 class="fw-bold mb-0">Anna Liu</h6>
                                <small class="text-muted">COO, DataFlow Systems</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center" data-aos="fade-up">
                <h2 class="display-6 fw-bold mb-3">Ready to transform your HR operations?</h2>
                <p class="lead text-muted mb-4">Join 10,000+ companies already using {{ config('app.name') }} to streamline their HR processes.</p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5 fw-semibold">
                        Start Free Trial <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-primary btn-lg px-5">
                        <i class="bi bi-calendar me-2"></i>Book a Demo
                    </a>
                </div>
                <p class="small text-muted mt-3"><i class="bi bi-shield-check me-1"></i> No credit card required. 14-day free trial.</p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.hero-section {
    min-height: 100vh;
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%);
}
.text-white-80 { color: rgba(255,255,255,0.8); }
.text-purple { color: #7c3aed; }
.bg-purple { background-color: #7c3aed; }
.tracking-wider { letter-spacing: 0.1em; }
.hover-shadow { transition: all 0.3s ease; }
.hover-shadow:hover { transform: translateY(-5px); box-shadow: 0 10px 40px rgba(0,0,0,0.1) !important; }
.border-white-10 { border-color: rgba(255,255,255,0.1) !important; }
.btn-icon { width: 36px; height: 36px; padding: 0; display: inline-flex; align-items: center; justify-content: center; }
</style>
@endpush
