<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name')) - {{ config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-sidebar d-none d-lg-flex">
            <div class="auth-sidebar-content">
                <div class="mb-5">
                    <div class="auth-brand d-flex align-items-center gap-3 mb-2">
                        <img src="{{ asset('images/logo.png') }}"
                             alt="CMS Pro"
                             style="width:60px;height:60px;object-fit:contain;"
                             class="me-3"> 

                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ asset('images/logo.png') }}"
                                     alt="CMS Pro Logo"
                                    class="auth-brand-logo">

                                <h2 class="text-white fw-bold mb-0">{{ config('app.name') }}</h2>
                            </div>
                    </div>

                    <p class="text-white-50 mb-0">
                       Company Management System
                    </p>
                </div>
                <div class="auth-testimonial">
                    <div class="mb-4">
                        <i class="bi bi-quote display-3 text-white-50"></i>
                    </div>
                    <blockquote class="text-white fs-5 fw-light lh-base mb-4">
                        "Simplify company operations with a centralized platform for employee management, attendance, projects, payroll, and real-time reporting.."
                    </blockquote>
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-circle bg-white text-primary fw-bold">CP</div>

                            <div>
                                <p class="text-white fw-semibold mb-0">CMS Pro Team</p>
                                <small class="text-white-50">
                                    Smarter company management
                                </small>
                            </div>
                    </div>
                </div>
                <div class="auth-features mt-5">
                    <div class="d-flex align-items-center gap-3 text-white-50 mb-3">
                        <i class="bi bi-check-circle-fill text-white"></i>
                        <span>Employee & Department Management</span>
                    </div>
                    <div class="d-flex align-items-center gap-3 text-white-50 mb-3">
                        <i class="bi bi-check-circle-fill text-white"></i>
                        <span>Attendance & Payroll Tracking</span>
                    </div>
                    <div class="d-flex align-items-center gap-3 text-white-50">
                        <i class="bi bi-check-circle-fill text-white"></i>
                        <span>Projects, Reports & Analytics</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="auth-form auth-form-decorated">
            <div class="auth-decoration auth-decoration-one"></div>
            <div class="auth-decoration auth-decoration-two"></div>
            <div class="auth-decoration auth-decoration-three"></div>

            <div class="auth-form-inner">
                <div class="text-end mb-4">
                    <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="bi bi-arrow-left me-1"></i> Back to Home
                    </a>
                </div>
                @yield('content')
                <p class="text-center text-muted small mt-5 mb-0">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.7/dist/axios.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
