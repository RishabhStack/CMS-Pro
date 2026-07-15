<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CMS Pro')</title>
    <meta name="description" content="@yield('meta_description', 'Modern Company Management Platform')">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="{{ asset('css/public.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="CMS Pro Logo" height="42" class="me-2">
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list fs-3"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('features') ? 'active' : '' }}" href="{{ route('features') }}">Features</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('pricing') ? 'active' : '' }}" href="{{ route('pricing') }}">Pricing</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a></li>
                </ul>
                <div class="d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn btn-outline-primary px-4">Sign In</a>
                    <a href="{{ route('register') }}" class="btn btn-primary px-4">Get Started</a>
                </div>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="bg-dark text-white pt-5 pb-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-building text-primary me-2"></i>CMS Pro</h5>
                    <p class="text-white-50 small">Modern Company Management Platform designed for modern organizations. Streamline your HR operations with our comprehensive suite of tools.</p>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-light btn-sm btn-icon rounded-circle"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm btn-icon rounded-circle"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm btn-icon rounded-circle"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm btn-icon rounded-circle"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="fw-semibold mb-3">Product</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ route('features') }}" class="text-white-50 text-decoration-none">Features</a></li>
                        <li class="mb-2"><a href="{{ route('pricing') }}" class="text-white-50 text-decoration-none">Pricing</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Integrations</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Changelog</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">API Docs</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="fw-semibold mb-3">Company</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ route('about') }}" class="text-white-50 text-decoration-none">About</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Blog</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Careers</a></li>
                        <li class="mb-2"><a href="{{ route('contact') }}" class="text-white-50 text-decoration-none">Contact</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Press Kit</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="fw-semibold mb-3">Support</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Help Center</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Documentation</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Community</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Status</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Security</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="fw-semibold mb-3">Legal</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ route('privacy') }}" class="text-white-50 text-decoration-none">Privacy Policy</a></li>
                        <li class="mb-2"><a href="{{ route('terms') }}" class="text-white-50 text-decoration-none">Terms of Service</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Cookie Policy</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">GDPR</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">SLA</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 border-white-10">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <small class="text-white-50">&copy; {{ date('Y') }} {{ config('CMS Pro') }}. All rights reserved.</small>
                </div>
                <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                    <small class="text-white-50">Developed by <a href="https://github.com/RishabhStack/CMS-Pro" target="_blank" rel="noopener" class="text-white text-decoration-none fw-semibold">Rishabh • Darshil • Henil</a></small>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });
    </script>
    @stack('scripts')
</body>
</html>
