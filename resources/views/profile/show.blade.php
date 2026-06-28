@extends('layouts.master')

@section('title', 'Profile')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <div class="avatar-circle bg-primary text-white mx-auto mb-3" style="width: 80px; height: 80px; font-size: 1.8rem;">
                    <span>{{ substr($user->first_name ?? 'U', 0, 1) }}{{ substr($user->last_name ?? '', 0, 1) }}</span>
                </div>
                <h5>{{ $user->first_name }} {{ $user->last_name }}</h5>
                <p class="text-muted mb-1">{{ $user->email }}</p>
                @if($user->employee)
                    <p class="text-muted mb-0">{{ $user->employee->designation->name ?? '' }}</p>
                    <p class="text-muted">{{ $user->employee->department->name ?? '' }}</p>
                @endif
                <span class="badge bg-soft-success">{{ ucfirst($user->roles->first()->name ?? 'User') }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Profile Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control" value="{{ $user->first_name }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="{{ $user->last_name }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ $user->phone ?? '' }}">
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @php
            $demoEmails = ['owner@example.com', 'admin@example.com', 'employee@example.com'];
        @endphp
        @if(!in_array($user->email, $demoEmails))
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Change Password</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Current Password</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">New Password</label>
                                <input type="password" name="new_password" class="form-control" required minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}" title="Must contain at least 8 characters, one uppercase, one lowercase, one number and one special character">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="new_password_confirmation" class="form-control" required>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="card mt-3">
                <div class="card-body text-center text-muted py-4">
                    <i class="bi bi-info-circle fs-3 d-block mb-2"></i>
                    <p class="mb-0">Password changes are disabled for demo accounts. Use a registered account to change your password.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
