@extends('layouts.master')

@section('title', 'Company Settings')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Company Settings</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('companies.update', $company->id ?? '') }}" method="POST" enctype="multipart/form-data" id="companyForm">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $company->name ?? '') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Short Name</label>
                        <input type="text" name="short_name" class="form-control @error('short_name') is-invalid @enderror" value="{{ old('short_name', $company->short_name ?? '') }}">
                        @error('short_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $company->email ?? '') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $company->phone ?? '') }}" required>
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Website</label>
                        <input type="url" name="website" class="form-control @error('website') is-invalid @enderror" value="{{ old('website', $company->website ?? '') }}">
                        @error('website')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Tax ID / Registration No.</label>
                        <input type="text" name="tax_id" class="form-control @error('tax_id') is-invalid @enderror" value="{{ old('tax_id', $company->tax_id ?? '') }}">
                        @error('tax_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', $company->address ?? '') }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Country</label>
                        <input type="text" name="country" class="form-control @error('country') is-invalid @enderror" value="{{ old('country', $company->country ?? '') }}">
                        @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">State</label>
                        <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state', $company->state ?? '') }}">
                        @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city', $company->city ?? '') }}">
                        @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">ZIP / Postal Code</label>
                        <input type="text" name="zip_code" class="form-control @error('zip_code') is-invalid @enderror" value="{{ old('zip_code', $company->zip_code ?? '') }}">
                        @error('zip_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Company Logo</label>
                        <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                        @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @if(isset($company) && $company->logo)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $company->logo) }}" alt="Company Logo" height="60" class="img-thumbnail">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="row g-3">
                <h6 class="fw-bold">Localization Settings</h6>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Timezone</label>
                        <select name="timezone" class="form-select select2 @error('timezone') is-invalid @enderror">
                            @foreach(timezone_identifiers_list() as $tz)
                                <option value="{{ $tz }}" {{ (old('timezone', $company->timezone ?? 'UTC') == $tz) ? 'selected' : '' }}>{{ $tz }}</option>
                            @endforeach
                        </select>
                        @error('timezone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Currency</label>
                        <select name="currency" class="form-select @error('currency') is-invalid @enderror">
                            <option value="USD" {{ (old('currency', $company->currency ?? 'USD') == 'USD') ? 'selected' : '' }}>USD ($)</option>
                            <option value="EUR" {{ (old('currency', $company->currency ?? 'USD') == 'EUR') ? 'selected' : '' }}>EUR (€)</option>
                            <option value="GBP" {{ (old('currency', $company->currency ?? 'USD') == 'GBP') ? 'selected' : '' }}>GBP (£)</option>
                            <option value="INR" {{ (old('currency', $company->currency ?? 'USD') == 'INR') ? 'selected' : '' }}>INR (₹)</option>
                        </select>
                        @error('currency')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Date Format</label>
                        <select name="date_format" class="form-select @error('date_format') is-invalid @enderror">
                            <option value="Y-m-d" {{ (old('date_format', $company->date_format ?? 'Y-m-d') == 'Y-m-d') ? 'selected' : '' }}>YYYY-MM-DD</option>
                            <option value="m/d/Y" {{ (old('date_format', $company->date_format ?? 'Y-m-d') == 'm/d/Y') ? 'selected' : '' }}>MM/DD/YYYY</option>
                            <option value="d/m/Y" {{ (old('date_format', $company->date_format ?? 'Y-m-d') == 'd/m/Y') ? 'selected' : '' }}>DD/MM/YYYY</option>
                            <option value="d M, Y" {{ (old('date_format', $company->date_format ?? 'Y-m-d') == 'd M, Y') ? 'selected' : '' }}>DD Mon, YYYY</option>
                        </select>
                        @error('date_format')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('.select2').select2({
            theme: 'bootstrap-5'
        });
    });

    App.form('#companyForm', {
        success: function () {
            App.toast('Company settings updated successfully.', 'success');
        }
    });
</script>
@endpush
