@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')
<div class="text-center mb-4">
    <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-inline-flex p-3 mb-3">
        <i class="bi bi-building fs-2"></i>
    </div>
    <h3 class="fw-bold mb-1">Welcome back</h3>
    <p class="text-muted">Sign in to your account to continue</p>
</div>

<form method="POST" action="{{ route('login') }}" id="loginForm" autocomplete="off">
    @csrf

    <div class="mb-3">
        <label for="email" class="form-label fw-medium">Email Address</label>
        <div class="input-group input-group-lg">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
            <input type="email" class="form-control bg-light border-start-0 ps-0 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@company.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label fw-medium">Password</label>
        <div class="input-group input-group-lg">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
            <input type="password" class="form-control bg-light border-start-0 ps-0 @error('password') is-invalid @enderror" id="password" name="password" required minlength="6" placeholder="Enter your password">
            <button class="btn btn-light border" type="button" id="togglePassword">
                <i class="bi bi-eye text-muted"></i>
            </button>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label small" for="remember">Remember me</label>
        </div>
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="small text-decoration-none fw-medium">Forgot password?</a>
        @endif
    </div>

    <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold" id="loginBtn">
        <span class="btn-text">Sign In</span>
        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
    </button>
</form>

<div class="text-center mt-4">
    <p class="mb-0 text-muted small">
        Don't have an account?
        <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">Create your organization</a>
    </p>
</div>

<div class="mt-4 pt-3 border-top text-center">
    <div class="row g-2">
        <div class="col-4">
            <small class="text-muted d-block fw-medium">owner@example.com</small>
            <small class="text-muted">Owner</small>
        </div>
        <div class="col-4">
            <small class="text-muted d-block fw-medium">admin@example.com</small>
            <small class="text-muted">Admin</small>
        </div>
        <div class="col-4">
            <small class="text-muted d-block fw-medium">employee@example.com</small>
            <small class="text-muted">Employee</small>
        </div>
        <div class="col-12 mt-1">
            <small class="text-muted fst-italic">Password: <strong>password</strong></small>
        </div>
    </div>
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

    App.form('#loginForm', {
        success: function (data) {
            if (data.redirect) {
                window.location.href = data.redirect;
            }
        },
    });
});
</script>
@endpush

