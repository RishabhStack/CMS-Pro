@extends('layouts.public')

@section('title', 'About Us')

@section('content')
<section class="py-5 mt-5">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3 rounded-pill">About Us</span>
                <h1 class="display-5 fw-bold mb-4">We're on a mission to simplify HR</h1>
                <p class="lead text-muted mb-4">Founded in 2020, {{ config('app.name') }} has grown from a small startup to a trusted HR platform serving thousands of organizations worldwide.</p>
                <p class="text-muted">We believe that great HR software should be intuitive, powerful, and accessible to organizations of all sizes. Our team of HR professionals and engineers work tirelessly to build a platform that automates mundane tasks, provides actionable insights, and empowers HR teams to focus on what matters most — people.</p>
                <div class="row g-4 mt-4">
                    <div class="col-4">
                        <div class="h3 fw-bold text-primary mb-0">500+</div>
                        <small class="text-muted">Customers</small>
                    </div>
                    <div class="col-4">
                        <div class="h3 fw-bold text-primary mb-0">50K+</div>
                        <small class="text-muted">Users</small>
                    </div>
                    <div class="col-4">
                        <div class="h3 fw-bold text-primary mb-0">98%</div>
                        <small class="text-muted">Satisfaction</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <img src="https://placehold.co/600x500/4f46e5/ffffff?text=Our+Team" alt="About Us" class="img-fluid rounded-4 shadow">
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container py-5">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-6 fw-bold">Our Values</h2>
            <p class="text-muted">The principles that guide everything we do.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="card-body">
                        <div class="display-1 text-primary mb-3">🎯</div>
                        <h5 class="fw-bold">Customer First</h5>
                        <p class="text-muted small">Every decision we make starts with our customers. Their success is our success.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="card-body">
                        <div class="display-1 text-primary mb-3">🚀</div>
                        <h5 class="fw-bold">Innovation</h5>
                        <p class="text-muted small">We continuously push boundaries to deliver cutting-edge HR solutions.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="card-body">
                        <div class="display-1 text-primary mb-3">🤝</div>
                        <h5 class="fw-bold">Trust & Security</h5>
                        <p class="text-muted small">We treat customer data with the utmost respect and maintain enterprise-grade security.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
