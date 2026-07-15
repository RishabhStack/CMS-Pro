@extends('layouts.public')

@section('title', 'Terms of Service')

@section('content')
<section class="py-5 mt-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h1 class="display-5 fw-bold mb-4">Terms of Service</h1>
                <p class="text-muted">Last updated: January 1, 2026</p>

                <div class="mt-5">
                    <h5 class="fw-bold mb-3">1. Acceptance of Terms</h5>
                    <p class="text-muted">By accessing and using {{ config('app.name') }}, you agree to be bound by these Terms of Service. If you do not agree, please do not use our platform.</p>

                    <h5 class="fw-bold mb-3 mt-4">2. Description of Service</h5>
                    <p class="text-muted">{{ config('app.name') }} provides a cloud-based Company Management System (CMS Pro) that includes employee management, payroll processing, attendance tracking, leave management, and related HR functionalities.</p>

                    <h5 class="fw-bold mb-3 mt-4">3. User Responsibilities</h5>
                    <p class="text-muted">You are responsible for maintaining the confidentiality of your account credentials, ensuring accurate data entry, and complying with all applicable laws regarding employee data and privacy.</p>

                    <h5 class="fw-bold mb-3 mt-4">4. Subscription & Billing</h5>
                    <p class="text-muted">Paid plans are billed in advance on a monthly or annual basis. You may cancel your subscription at any time. Refunds are provided according to our refund policy.</p>

                    <h5 class="fw-bold mb-3 mt-4">5. Data Ownership</h5>
                    <p class="text-muted">You retain full ownership of all data you enter into the platform. We claim no intellectual property rights over your data.</p>

                    <h5 class="fw-bold mb-3 mt-4">6. Service Level</h5>
                    <p class="text-muted">We strive to maintain 99.9% uptime. Scheduled maintenance will be communicated in advance. We are not liable for downtime beyond our reasonable control.</p>

                    <h5 class="fw-bold mb-3 mt-4">7. Limitation of Liability</h5>
                    <p class="text-muted">{{ config('app.name') }} shall not be liable for any indirect, incidental, or consequential damages arising from the use of our platform.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
