@extends('layouts.public')

@section('title', 'Pricing')

@section('content')
<section class="py-5 mt-5">
    <div class="container py-5">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3 rounded-pill">Pricing Plans</span>
            <h1 class="display-5 fw-bold mb-3">Simple, transparent pricing</h1>
            <p class="lead text-muted mx-auto" style="max-width: 500px;">No hidden fees. No surprises. Scale as you grow.</p>
            <div class="d-inline-flex bg-light rounded-3 p-1 mt-3">
                <button class="btn btn-sm btn-primary rounded-3 px-4 py-2" id="monthlyBtn">Monthly</button>
                <button class="btn btn-sm btn-link text-dark px-4 py-2" id="yearlyBtn">Yearly <span class="badge bg-success ms-1">Save 20%</span></button>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <h5 class="fw-bold">Starter</h5>
                            <p class="text-muted small">Perfect for small teams</p>
                            <div class="display-5 fw-bold mb-0"><span class="price">$29</span><span class="fs-6 text-muted fw-normal">/mo</span></div>
                            <small class="text-muted yearly-note d-none">Billed annually ($290/yr)</small>
                        </div>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Up to 10 employees</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Core HR features</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Leave management</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Attendance tracking</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Basic reports</li>
                            <li class="mb-2 text-muted"><i class="bi bi-x-circle me-2"></i>Payroll automation</li>
                            <li class="mb-2 text-muted"><i class="bi bi-x-circle me-2"></i>API access</li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary w-100">Get Started</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-sm h-100 border-primary border-2">
                    <div class="card-body p-4 position-relative">
                        <span class="position-absolute top-0 end-0 bg-primary text-white px-3 py-1 small rounded-bl-3">Popular</span>
                        <div class="mb-4">
                            <h5 class="fw-bold">Business</h5>
                            <p class="text-muted small">Best for growing companies</p>
                            <div class="display-5 fw-bold mb-0"><span class="price">$79</span><span class="fs-6 text-muted fw-normal">/mo</span></div>
                            <small class="text-muted yearly-note d-none">Billed annually ($790/yr)</small>
                        </div>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Up to 50 employees</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>All Starter features</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Payroll management</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Document management</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Advanced reports</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>API access</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Priority support</li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn btn-primary w-100">Get Started</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <h5 class="fw-bold">Enterprise</h5>
                            <p class="text-muted small">For large organizations</p>
                            <div class="display-5 fw-bold mb-0"><span class="price">$199</span><span class="fs-6 text-muted fw-normal">/mo</span></div>
                            <small class="text-muted yearly-note d-none">Billed annually ($1,990/yr)</small>
                        </div>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Unlimited employees</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>All Business features</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Custom roles & permissions</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>White labeling</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>SSO / SAML</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Dedicated account manager</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Custom integrations</li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary w-100">Contact Sales</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    function updatePrice(btn, isYearly) {
        const amounts = { starter: 290, business: 790, enterprise: 1990 };
        const monthly = { starter: 29, business: 79, enterprise: 199 };
        document.querySelectorAll('.card').forEach((card, i) => {
            const key = ['starter', 'business', 'enterprise'][i];
            const priceEl = card.querySelector('.price');
            const note = card.querySelector('.yearly-note');
            if (isYearly) {
                priceEl.textContent = '$' + amounts[key];
                if (note) note.classList.remove('d-none');
            } else {
                priceEl.textContent = '$' + monthly[key];
                if (note) note.classList.add('d-none');
            }
        });
        btn.closest('.d-inline-flex').querySelector('.btn-primary')?.classList.remove('btn-primary');
        btn.closest('.d-inline-flex').querySelector('.btn-primary')?.classList.add('btn-link', 'text-dark');
        btn.classList.remove('btn-link', 'text-dark');
        btn.classList.add('btn-primary');
    }
    $('#monthlyBtn').on('click', function () { updatePrice(this, false); });
    $('#yearlyBtn').on('click', function () { updatePrice(this, true); });
});
</script>
@endpush
