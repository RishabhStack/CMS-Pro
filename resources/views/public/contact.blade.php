@extends('layouts.public')

@section('title', 'Contact Us')

@section('content')
<section class="py-5 mt-5">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3 rounded-pill">Get in Touch</span>
                <h1 class="display-5 fw-bold mb-4">We'd love to hear from you</h1>
                <p class="lead text-muted mb-4">Have questions about our platform? Want a personalized demo? Our team is here to help.</p>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                        <i class="bi bi-envelope fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Email Us</h6>
                        <p class="text-muted small mb-0">hello@example.com<br>support@example.com</p>
                    </div>
                </div>
                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                        <i class="bi bi-telephone fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Call Us</h6>
                        <p class="text-muted small mb-0">+1 (555) 123-4567<br>Mon-Fri 9am-6pm EST</p>
                    </div>
                </div>
                <div class="d-flex align-items-start gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                        <i class="bi bi-geo-alt fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Visit Us</h6>
                        <p class="text-muted small mb-0">123 Business Avenue, Suite 100<br>San Francisco, CA 94105</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Send us a message</h5>
                        <form id="contactForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="first_name" required placeholder="John">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="last_name" required placeholder="Doe">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" required placeholder="john@example.com">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control" name="phone" placeholder="+1 (555) 123-4567">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Subject <span class="text-danger">*</span></label>
                                    <select class="form-select" name="subject" required>
                                        <option value="">Select a subject</option>
                                        <option value="general">General Inquiry</option>
                                        <option value="sales">Sales Question</option>
                                        <option value="support">Technical Support</option>
                                        <option value="demo">Request a Demo</option>
                                        <option value="partnership">Partnership Opportunity</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Message <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="message" rows="4" required placeholder="Tell us how we can help..."></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100 btn-lg">Send Message <i class="bi bi-send ms-2"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-3 bg-dark text-white text-center">
    <div class="container">
        <small>Developed by <a href="https://milinddaraniya.com" target="_blank" rel="noopener" class="text-white text-decoration-none fw-semibold">Milind Daraniya</a></small>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#contactForm').on('submit', function (e) {
        e.preventDefault();
        const btn = $(this).find('[type="submit"]');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Sending...');
        setTimeout(function () {
            App.success('Thank you! We will get back to you within 24 hours.');
            btn.prop('disabled', false).html('Send Message <i class="bi bi-send ms-2"></i>');
            $('#contactForm')[0].reset();
        }, 1500);
    });
});
</script>
@endpush
