@extends('layouts.public')

@section('title', 'Privacy Policy')

@section('content')
<section class="py-5 mt-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h1 class="display-5 fw-bold mb-4">Privacy Policy</h1>
                <p class="text-muted">Last updated: January 1, 2026</p>

                <div class="mt-5">
                    <h5 class="fw-bold mb-3">1. Information We Collect</h5>
                    <p class="text-muted">We collect information you provide directly to us, including name, email address, phone number, company details, and employment information necessary for HR management purposes.</p>

                    <h5 class="fw-bold mb-3 mt-4">2. How We Use Your Information</h5>
                    <p class="text-muted">We use the collected information to provide and improve our HRMS services, process payroll, manage employee records, comply with legal obligations, and communicate with you about our services.</p>

                    <h5 class="fw-bold mb-3 mt-4">3. Data Security</h5>
                    <p class="text-muted">We implement industry-standard security measures including encryption at rest and in transit, regular security audits, and strict access controls to protect your data.</p>

                    <h5 class="fw-bold mb-3 mt-4">4. Data Retention</h5>
                    <p class="text-muted">We retain your data for as long as your account is active or as needed to provide services. You can request data deletion at any time by contacting our support team.</p>

                    <h5 class="fw-bold mb-3 mt-4">5. Third-Party Services</h5>
                    <p class="text-muted">We may share data with third-party service providers who assist in our operations (payment processing, cloud hosting) under strict confidentiality agreements.</p>

                    <h5 class="fw-bold mb-3 mt-4">6. Your Rights</h5>
                    <p class="text-muted">You have the right to access, correct, delete, or port your data. You can manage most of these through your account settings or by contacting our privacy team.</p>

                    <h5 class="fw-bold mb-3 mt-4">7. Contact Us</h5>
                    <p class="text-muted">For privacy-related inquiries, please contact us at privacy@example.com or through our <a href="{{ route('contact') }}">contact page</a>.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
