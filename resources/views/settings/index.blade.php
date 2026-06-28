@extends('layouts.master')

@section('title', 'Settings')

@section('content')
<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" id="settingsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                    <i class="bi bi-building"></i> General
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="localization-tab" data-bs-toggle="tab" data-bs-target="#localization" type="button" role="tab">
                    <i class="bi bi-globe"></i> Localization
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="attendance-tab" data-bs-toggle="tab" data-bs-target="#attendance" type="button" role="tab">
                    <i class="bi bi-clock"></i> Attendance
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="leave-tab" data-bs-toggle="tab" data-bs-target="#leave" type="button" role="tab">
                    <i class="bi bi-calendar-check"></i> Leave
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="notification-tab" data-bs-toggle="tab" data-bs-target="#notification" type="button" role="tab">
                    <i class="bi bi-bell"></i> Notifications
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="system-tab" data-bs-toggle="tab" data-bs-target="#system" type="button" role="tab">
                    <i class="bi bi-gear"></i> System
                </button>
            </li>
            @if(auth()->user()->isOwner())
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-danger" id="danger-tab" data-bs-toggle="tab" data-bs-target="#danger" type="button" role="tab">
                        <i class="bi bi-exclamation-triangle"></i> Danger Zone
                    </button>
                </li>
            @endif
        </ul>
    </div>
    <div class="card-body">
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="tab-content" id="settingsTabsContent">
                <div class="tab-pane fade show active" id="general" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Company Name <span class="text-danger">*</span></label>
                                <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name', $settings['company_name'] ?? '') }}" required>
                                @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Company Email</label>
                                <input type="email" name="company_email" class="form-control @error('company_email') is-invalid @enderror" value="{{ old('company_email', $settings['company_email'] ?? '') }}">
                                @error('company_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Company Phone</label>
                                <input type="text" name="company_phone" class="form-control @error('company_phone') is-invalid @enderror" value="{{ old('company_phone', $settings['company_phone'] ?? '') }}">
                                @error('company_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Company Address</label>
                                <input type="text" name="company_address" class="form-control @error('company_address') is-invalid @enderror" value="{{ old('company_address', $settings['company_address'] ?? '') }}">
                                @error('company_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Company Logo</label>
                                <input type="file" name="company_logo" class="form-control @error('company_logo') is-invalid @enderror" accept="image/*">
                                @error('company_logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @if(isset($settings['company_logo']) && $settings['company_logo'])
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $settings['company_logo']) }}" alt="Logo" height="60" class="img-thumbnail">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="localization" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Timezone</label>
                                <select name="timezone" class="form-select select2 @error('timezone') is-invalid @enderror">
                                    @foreach(timezone_identifiers_list() as $tz)
                                        <option value="{{ $tz }}" {{ (old('timezone', $settings['timezone'] ?? 'UTC') == $tz) ? 'selected' : '' }}>{{ $tz }}</option>
                                    @endforeach
                                </select>
                                @error('timezone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Currency</label>
                                <select name="currency" class="form-select @error('currency') is-invalid @enderror">
                                    <option value="USD" {{ (old('currency', $settings['currency'] ?? 'USD') == 'USD') ? 'selected' : '' }}>USD ($)</option>
                                    <option value="EUR" {{ (old('currency', $settings['currency'] ?? 'USD') == 'EUR') ? 'selected' : '' }}>EUR (€)</option>
                                    <option value="GBP" {{ (old('currency', $settings['currency'] ?? 'USD') == 'GBP') ? 'selected' : '' }}>GBP (£)</option>
                                    <option value="INR" {{ (old('currency', $settings['currency'] ?? 'USD') == 'INR') ? 'selected' : '' }}>INR (₹)</option>
                                </select>
                                @error('currency')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Date Format</label>
                                <select name="date_format" class="form-select @error('date_format') is-invalid @enderror">
                                    <option value="Y-m-d" {{ (old('date_format', $settings['date_format'] ?? 'Y-m-d') == 'Y-m-d') ? 'selected' : '' }}>YYYY-MM-DD</option>
                                    <option value="m/d/Y" {{ (old('date_format', $settings['date_format'] ?? 'Y-m-d') == 'm/d/Y') ? 'selected' : '' }}>MM/DD/YYYY</option>
                                    <option value="d/m/Y" {{ (old('date_format', $settings['date_format'] ?? 'Y-m-d') == 'd/m/Y') ? 'selected' : '' }}>DD/MM/YYYY</option>
                                    <option value="d M, Y" {{ (old('date_format', $settings['date_format'] ?? 'Y-m-d') == 'd M, Y') ? 'selected' : '' }}>DD Mon, YYYY</option>
                                </select>
                                @error('date_format')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Time Format</label>
                                <select name="time_format" class="form-select @error('time_format') is-invalid @enderror">
                                    <option value="H:i" {{ (old('time_format', $settings['time_format'] ?? 'H:i') == 'H:i') ? 'selected' : '' }}>24 Hours (14:30)</option>
                                    <option value="h:i A" {{ (old('time_format', $settings['time_format'] ?? 'H:i') == 'h:i A') ? 'selected' : '' }}>12 Hours (02:30 PM)</option>
                                </select>
                                @error('time_format')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Language</label>
                                <select name="language" class="form-select @error('language') is-invalid @enderror">
                                    <option value="en" {{ (old('language', $settings['language'] ?? 'en') == 'en') ? 'selected' : '' }}>English</option>
                                    <option value="es" {{ (old('language', $settings['language'] ?? 'en') == 'es') ? 'selected' : '' }}>Spanish</option>
                                    <option value="fr" {{ (old('language', $settings['language'] ?? 'en') == 'fr') ? 'selected' : '' }}>French</option>
                                    <option value="de" {{ (old('language', $settings['language'] ?? 'en') == 'de') ? 'selected' : '' }}>German</option>
                                </select>
                                @error('language')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="attendance" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Work Hours Per Day</label>
                                <input type="number" name="work_hours_per_day" step="0.5" class="form-control @error('work_hours_per_day') is-invalid @enderror" value="{{ old('work_hours_per_day', $settings['work_hours_per_day'] ?? 8) }}">
                                @error('work_hours_per_day')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Work Days Per Week</label>
                                <select name="work_days_per_week" class="form-select @error('work_days_per_week') is-invalid @enderror">
                                    @foreach(range(5, 7) as $days)
                                        <option value="{{ $days }}" {{ (old('work_days_per_week', $settings['work_days_per_week'] ?? 5) == $days) ? 'selected' : '' }}>{{ $days }} Days</option>
                                    @endforeach
                                </select>
                                @error('work_days_per_week')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Grace Period (minutes)</label>
                                <input type="number" name="grace_period" class="form-control @error('grace_period') is-invalid @enderror" value="{{ old('grace_period', $settings['grace_period'] ?? 15) }}">
                                @error('grace_period')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Late Threshold (minutes)</label>
                                <input type="number" name="late_threshold" class="form-control @error('late_threshold') is-invalid @enderror" value="{{ old('late_threshold', $settings['late_threshold'] ?? 30) }}">
                                @error('late_threshold')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Half Day Threshold (hours)</label>
                                <input type="number" name="half_day_threshold" step="0.5" class="form-control @error('half_day_threshold') is-invalid @enderror" value="{{ old('half_day_threshold', $settings['half_day_threshold'] ?? 4) }}">
                                @error('half_day_threshold')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="leave" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Approval Workflow</label>
                                <select name="leave_approval_workflow" class="form-select @error('leave_approval_workflow') is-invalid @enderror">
                                    <option value="single" {{ (old('leave_approval_workflow', $settings['leave_approval_workflow'] ?? 'single') == 'single') ? 'selected' : '' }}>Single Level</option>
                                    <option value="multi" {{ (old('leave_approval_workflow', $settings['leave_approval_workflow'] ?? 'single') == 'multi') ? 'selected' : '' }}>Multi Level</option>
                                </select>
                                @error('leave_approval_workflow')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Max Consecutive Leave Days</label>
                                <input type="number" name="max_consecutive_leave_days" class="form-control @error('max_consecutive_leave_days') is-invalid @enderror" value="{{ old('max_consecutive_leave_days', $settings['max_consecutive_leave_days'] ?? 30) }}">
                                @error('max_consecutive_leave_days')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check form-switch mt-4">
                                    <input type="hidden" name="leave_carry_forward" value="0">
                                    <input class="form-check-input" type="checkbox" name="leave_carry_forward" value="1" id="leaveCarryForward" {{ old('leave_carry_forward', $settings['leave_carry_forward'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="leaveCarryForward">Enable Leave Carry Forward</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check form-switch mt-4">
                                    <input type="hidden" name="leave_encashment" value="0">
                                    <input class="form-check-input" type="checkbox" name="leave_encashment" value="1" id="leaveEncashment" {{ old('leave_encashment', $settings['leave_encashment'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="leaveEncashment">Enable Leave Encashment</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="notification" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="email_notifications" value="1" id="emailNotifications" {{ old('email_notifications', $settings['email_notifications'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="emailNotifications">Enable Email Notifications</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="leave_application_notification" value="1" id="leaveAppNotification" {{ old('leave_application_notification', $settings['leave_application_notification'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="leaveAppNotification">Notify on Leave Applications</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="leave_approval_notification" value="1" id="leaveApprovalNotification" {{ old('leave_approval_notification', $settings['leave_approval_notification'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="leaveApprovalNotification">Notify on Leave Approvals</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="birthday_notification" value="1" id="birthdayNotification" {{ old('birthday_notification', $settings['birthday_notification'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="birthdayNotification">Notify on Employee Birthdays</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="anniversary_notification" value="1" id="anniversaryNotification" {{ old('anniversary_notification', $settings['anniversary_notification'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="anniversaryNotification">Notify on Work Anniversaries</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="system" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Rows Per Page (DataTables)</label>
                                <select name="rows_per_page" class="form-select @error('rows_per_page') is-invalid @enderror">
                                    @foreach([10, 25, 50, 100] as $count)
                                        <option value="{{ $count }}" {{ (old('rows_per_page', $settings['rows_per_page'] ?? 10) == $count) ? 'selected' : '' }}>{{ $count }}</option>
                                    @endforeach
                                </select>
                                @error('rows_per_page')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Theme Color</label>
                                <select name="theme_color" class="form-select @error('theme_color') is-invalid @enderror">
                                    <option value="blue" {{ (old('theme_color', $settings['theme_color'] ?? 'blue') == 'blue') ? 'selected' : '' }}>Blue</option>
                                    <option value="indigo" {{ (old('theme_color', $settings['theme_color'] ?? 'blue') == 'indigo') ? 'selected' : '' }}>Indigo</option>
                                    <option value="purple" {{ (old('theme_color', $settings['theme_color'] ?? 'blue') == 'purple') ? 'selected' : '' }}>Purple</option>
                                    <option value="pink" {{ (old('theme_color', $settings['theme_color'] ?? 'blue') == 'pink') ? 'selected' : '' }}>Pink</option>
                                    <option value="red" {{ (old('theme_color', $settings['theme_color'] ?? 'blue') == 'red') ? 'selected' : '' }}>Red</option>
                                    <option value="orange" {{ (old('theme_color', $settings['theme_color'] ?? 'blue') == 'orange') ? 'selected' : '' }}>Orange</option>
                                    <option value="green" {{ (old('theme_color', $settings['theme_color'] ?? 'blue') == 'green') ? 'selected' : '' }}>Green</option>
                                </select>
                                @error('theme_color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" name="dark_mode" value="1" id="darkMode" {{ old('dark_mode', $settings['dark_mode'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="darkMode">Enable Dark Mode</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->isOwner())
                    <div class="tab-pane fade" id="danger" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="alert alert-danger d-flex align-items-center gap-2">
                                    <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                                    <div>
                                        <strong>Warning:</strong> Deleting an account permanently removes the user and all associated data (attendance, leaves, documents, payroll records, etc.). This action cannot be undone.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Select User to Delete</label>
                                    <select id="deleteUserSelect" class="form-select select2" style="width: 100%;">
                                        <option value="">Select a user...</option>
                                        @foreach(\App\Models\User::byCompany(company()->id)->where('id', '!=', auth()->id())->get() as $u)
                                            <option value="{{ $u->id }}">{{ $u->first_name }} {{ $u->last_name }} ({{ $u->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="mb-3">
                                    <button type="button" class="btn btn-danger" id="deleteAccountBtn" disabled onclick="confirmDeleteAccount()">
                                        <i class="bi bi-trash3"></i> Permanently Delete Account
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-4 border-top pt-3">
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

        $('#deleteUserSelect').on('change', function () {
            $('#deleteAccountBtn').prop('disabled', !$(this).val());
        });
    });

    function confirmDeleteAccount() {
        const userId = $('#deleteUserSelect').val();
        const userName = $('#deleteUserSelect option:selected').text();
        if (!userId) return;

        Swal.fire({
            title: 'Permanently Delete Account?',
            html: `Are you sure you want to delete <strong>${userName}</strong>?<br><br>
                   <span class="text-danger">This will permanently remove all associated data including attendance, leaves, documents, payroll records, and more. This action cannot be undone.</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Yes, delete permanently',
            cancelButtonText: 'Cancel',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return axios.delete('{{ route("settings.delete-account", "") }}/' + userId, {
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                }).then(response => {
                    App.toast(response.data.message || 'Account deleted successfully.', 'success');
                    $('#deleteUserSelect').val('').trigger('change');
                }).catch(error => {
                    const msg = error.response?.data?.message || 'Failed to delete account.';
                    App.toast(msg, 'error');
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        });
    }
</script>
@endpush
