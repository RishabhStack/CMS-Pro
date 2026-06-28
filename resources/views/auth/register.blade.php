@extends('layouts.auth')

@section('title', 'Create Organization')

@section('content')
<div class="text-center mb-4">
    <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-inline-flex p-3 mb-3">
        <i class="bi bi-rocket-takeoff fs-2"></i>
    </div>
    <h3 class="fw-bold mb-1">Create your organization</h3>
    <p class="text-muted">Set up your company and admin account in minutes</p>
</div>

<form method="POST" action="{{ route('register') }}" id="registerForm" autocomplete="off">
    @csrf

    <div class="bg-light rounded-3 p-3 mb-4">
        <h6 class="fw-semibold mb-3">
            <i class="bi bi-building text-primary me-1"></i> Company Details
        </h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="company_name" class="form-label small fw-medium">Company Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name" value="{{ old('company_name') }}" required placeholder="Milind Inc.">
                @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="company_email" class="form-label small fw-medium">Company Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control @error('company_email') is-invalid @enderror" id="company_email" name="company_email" value="{{ old('company_email') }}" required placeholder="info@example.com">
                @error('company_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="bg-light rounded-3 p-3 mb-4">
        <h6 class="fw-semibold mb-3">
            <i class="bi bi-person-badge text-primary me-1"></i> Admin Account
        </h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="first_name" class="form-label small fw-medium">First Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" required placeholder="John">
                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="last_name" class="form-label small fw-medium">Last Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" required placeholder="Doe">
                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label small fw-medium">Admin Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="admin@example.com">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="phone" class="form-label small fw-medium">Phone Number</label>
                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+1 (555) 123-4567">
                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="password" class="form-label small fw-medium">Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" id="password" name="password" required minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}" title="Must contain at least 8 characters, one uppercase, one lowercase, one number and one special character" placeholder="Min. 8 characters">
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="bi bi-eye"></i>
                    </button>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-6">
                <label for="password_confirmation" class="form-label small fw-medium">Confirm Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-lock-fill text-muted"></i></span>
                    <input type="password" class="form-control border-start-0" id="password_confirmation" name="password_confirmation" required placeholder="Repeat password">
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <div class="form-check">
            <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" name="terms" id="terms" {{ old('terms') ? 'checked' : '' }}>
            <label class="form-check-label small" for="terms">
                I agree to the <a href="{{ route('terms') }}" target="_blank" class="text-decoration-none fw-medium">Terms of Service</a> and <a href="{{ route('privacy') }}" target="_blank" class="text-decoration-none fw-medium">Privacy Policy</a>
                <span class="text-danger">*</span>
            </label>
            @error('terms')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold" id="registerBtn">
        <span class="btn-text">Create Organization</span>
        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
    </button>
</form>

<div class="text-center mt-4">
    <p class="mb-0 text-muted small">
        Already have an account?
        <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">Sign in</a>
    </p>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#togglePassword').on('click', function () {
        const password = $('#password');
        const icon = $(this).find('i');
        if (password.attr('type') === 'password') {
            password.attr('type', 'text');
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
        } else {
            password.attr('type', 'password');
            icon.removeClass('bi-eye-slash').addClass('bi-eye');
        }
    });

    App.form('#registerForm', {
        success: function (data) {
            if (data.redirect) {
                window.location.href = data.redirect;
            }
        },
    });
});
</script>
@endpush
