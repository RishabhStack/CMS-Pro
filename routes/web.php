<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\SalaryComponentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\CalendarController;

// Public Website Routes
Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('features', [FrontendController::class, 'features'])->name('features');
Route::get('pricing', [FrontendController::class, 'pricing'])->name('pricing');
Route::get('about', [FrontendController::class, 'about'])->name('about');
Route::get('contact', [FrontendController::class, 'contact'])->name('contact');
Route::get('privacy', [FrontendController::class, 'privacy'])->name('privacy');
Route::get('terms', [FrontendController::class, 'terms'])->name('terms');

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'company', 'company.context'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::post('update', [ProfileController::class, 'update'])->name('update');
        Route::post('password', [ProfileController::class, 'password'])->name('password');
        Route::post('avatar', [ProfileController::class, 'avatar'])->name('avatar');
    });

    // Companies
    Route::prefix('companies')->name('companies.')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('index');
        Route::get('list', [CompanyController::class, 'list'])->name('list');
        Route::get('{id}', [CompanyController::class, 'show'])->name('show');
        Route::post('/', [CompanyController::class, 'store'])->name('store');
        Route::put('{id}', [CompanyController::class, 'update'])->name('update');
        Route::delete('{id}', [CompanyController::class, 'destroy'])->name('destroy');
    });

    // Departments
    Route::prefix('departments')->name('departments.')->group(function () {
        Route::get('/', [DepartmentController::class, 'index'])->name('index');
        Route::get('list', [DepartmentController::class, 'list'])->name('list');
        Route::get('create', [DepartmentController::class, 'create'])->name('create');
        Route::get('{id}/edit', [DepartmentController::class, 'edit'])->name('edit');
        Route::get('{id}', [DepartmentController::class, 'show'])->name('show');
        Route::post('/', [DepartmentController::class, 'store'])->name('store');
        Route::put('{id}', [DepartmentController::class, 'update'])->name('update');
        Route::delete('{id}', [DepartmentController::class, 'destroy'])->name('destroy');
    });

    // Designations
    Route::prefix('designations')->name('designations.')->group(function () {
        Route::get('/', [DesignationController::class, 'index'])->name('index');
        Route::get('list', [DesignationController::class, 'list'])->name('list');
        Route::get('create', [DesignationController::class, 'create'])->name('create');
        Route::get('{id}/edit', [DesignationController::class, 'edit'])->name('edit');
        Route::get('{id}', [DesignationController::class, 'show'])->name('show');
        Route::post('/', [DesignationController::class, 'store'])->name('store');
        Route::put('{id}', [DesignationController::class, 'update'])->name('update');
        Route::delete('{id}', [DesignationController::class, 'destroy'])->name('destroy');
    });

    // Employees
    Route::prefix('employees')->name('employees.')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('index');
        Route::get('list', [EmployeeController::class, 'list'])->name('list');
        Route::get('create', [EmployeeController::class, 'create'])->name('create');
        Route::get('{id}/edit', [EmployeeController::class, 'edit'])->name('edit');
        Route::get('{id}', [EmployeeController::class, 'show'])->name('show');
        Route::post('/', [EmployeeController::class, 'store'])->name('store');
        Route::put('{id}', [EmployeeController::class, 'update'])->name('update');
        Route::delete('{id}', [EmployeeController::class, 'destroy'])->name('destroy');
    });

    // Roles
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('list', [RoleController::class, 'list'])->name('list');
        Route::get('create', [RoleController::class, 'create'])->name('create');
        Route::get('{id}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::get('{id}/permissions', [RoleController::class, 'managePermissions'])->name('manage-permissions');
        Route::get('{id}', [RoleController::class, 'show'])->name('show');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::put('{id}', [RoleController::class, 'update'])->name('update');
        Route::delete('{id}', [RoleController::class, 'destroy'])->name('destroy');
        Route::get('permissions/list', [RoleController::class, 'permissions'])->name('permissions');
    });

    // Attendance
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::get('list', [AttendanceController::class, 'list'])->name('list');
        Route::get('create', [AttendanceController::class, 'create'])->name('create');
        Route::get('{id}/edit', [AttendanceController::class, 'edit'])->name('edit');
        Route::post('clock-in', [AttendanceController::class, 'clockIn'])->name('clock-in');
        Route::post('clock-out', [AttendanceController::class, 'clockOut'])->name('clock-out');
        Route::post('break-start', [AttendanceController::class, 'markBreak'])->name('break-start');
        Route::post('break-end', [AttendanceController::class, 'endBreak'])->name('break-end');
        Route::get('{id}', [AttendanceController::class, 'show'])->name('show');
        Route::post('/', [AttendanceController::class, 'store'])->name('store');
        Route::put('{id}', [AttendanceController::class, 'update'])->name('update');
        Route::delete('{id}', [AttendanceController::class, 'destroy'])->name('destroy');
    });

    // Leaves
    Route::prefix('leaves')->name('leaves.')->group(function () {
        Route::get('/', [LeaveController::class, 'index'])->name('index');
        Route::get('list', [LeaveController::class, 'list'])->name('list');
        Route::get('create', [LeaveController::class, 'create'])->name('create');
        Route::get('{id}', [LeaveController::class, 'show'])->name('show');
        Route::post('/', [LeaveController::class, 'store'])->name('store');
        Route::post('{id}/approve', [LeaveController::class, 'approve'])->name('approve');
        Route::post('{id}/reject', [LeaveController::class, 'reject'])->name('reject');
        Route::post('{id}/cancel', [LeaveController::class, 'cancel'])->name('cancel');
        Route::delete('{id}', [LeaveController::class, 'destroy'])->name('destroy');
    });

    // Leave Types
    Route::prefix('leave-types')->name('leave-types.')->group(function () {
        Route::get('/', [LeaveTypeController::class, 'index'])->name('index');
        Route::get('list', [LeaveTypeController::class, 'list'])->name('list');
        Route::get('create', [LeaveTypeController::class, 'create'])->name('create');
        Route::get('{id}/edit', [LeaveTypeController::class, 'edit'])->name('edit');
        Route::get('{id}', [LeaveTypeController::class, 'show'])->name('show');
        Route::post('/', [LeaveTypeController::class, 'store'])->name('store');
        Route::put('{id}', [LeaveTypeController::class, 'update'])->name('update');
        Route::delete('{id}', [LeaveTypeController::class, 'destroy'])->name('destroy');
    });

    // Holidays
    Route::prefix('holidays')->name('holidays.')->group(function () {
        Route::get('/', [HolidayController::class, 'index'])->name('index');
        Route::get('list', [HolidayController::class, 'list'])->name('list');
        Route::get('create', [HolidayController::class, 'create'])->name('create');
        Route::get('{id}/edit', [HolidayController::class, 'edit'])->name('edit');
        Route::get('{id}', [HolidayController::class, 'show'])->name('show');
        Route::post('/', [HolidayController::class, 'store'])->name('store');
        Route::put('{id}', [HolidayController::class, 'update'])->name('update');
        Route::delete('{id}', [HolidayController::class, 'destroy'])->name('destroy');
    });

    // Salary Components
    Route::prefix('salary-components')->name('salary-components.')->group(function () {
        Route::get('/', [SalaryComponentController::class, 'index'])->name('index');
        Route::get('list', [SalaryComponentController::class, 'list'])->name('list');
        Route::get('create', [SalaryComponentController::class, 'create'])->name('create');
        Route::get('{id}/edit', [SalaryComponentController::class, 'edit'])->name('edit');
        Route::get('{id}', [SalaryComponentController::class, 'show'])->name('show');
        Route::post('/', [SalaryComponentController::class, 'store'])->name('store');
        Route::put('{id}', [SalaryComponentController::class, 'update'])->name('update');
        Route::delete('{id}', [SalaryComponentController::class, 'destroy'])->name('destroy');
    });

    // Payroll
    Route::prefix('payroll')->name('payroll.')->group(function () {
        Route::get('/', [PayrollController::class, 'index'])->name('index');
        Route::get('list', [PayrollController::class, 'list'])->name('list');
        Route::get('create', [PayrollController::class, 'create'])->name('create');
        Route::post('generate', [PayrollController::class, 'generate'])->name('generate');
        Route::get('{id}', [PayrollController::class, 'show'])->name('show');
        Route::put('{id}', [PayrollController::class, 'update'])->name('update');
        Route::delete('{id}', [PayrollController::class, 'destroy'])->name('destroy');
        Route::post('bulk-process', [PayrollController::class, 'bulkProcess'])->name('bulk-process');
    });

    // Documents
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('index');
        Route::get('list', [DocumentController::class, 'list'])->name('list');
        Route::get('create', [DocumentController::class, 'create'])->name('create');
        Route::get('{id}', [DocumentController::class, 'show'])->name('show');
        Route::post('/', [DocumentController::class, 'store'])->name('store');
        Route::get('{id}/download', [DocumentController::class, 'download'])->name('download');
        Route::delete('{id}', [DocumentController::class, 'destroy'])->name('destroy');
    });

    // Announcements
    Route::prefix('announcements')->name('announcements.')->group(function () {
        Route::get('/', [AnnouncementController::class, 'index'])->name('index');
        Route::get('list', [AnnouncementController::class, 'list'])->name('list');
        Route::get('create', [AnnouncementController::class, 'create'])->name('create');
        Route::get('{id}/edit', [AnnouncementController::class, 'edit'])->name('edit');
        Route::get('{id}', [AnnouncementController::class, 'show'])->name('show');
        Route::post('/', [AnnouncementController::class, 'store'])->name('store');
        Route::put('{id}', [AnnouncementController::class, 'update'])->name('update');
        Route::delete('{id}', [AnnouncementController::class, 'destroy'])->name('destroy');
    });

    // Calendar
    Route::prefix('calendar')->name('calendar.')->group(function () {
        Route::get('/', [CalendarController::class, 'index'])->name('index');
        Route::get('events', [CalendarController::class, 'events'])->name('events');
    });

    // Help
    Route::get('help', [HelpController::class, 'index'])->name('help');

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::match(['post', 'put'], 'update', [SettingController::class, 'update'])->name('update');
        Route::post('theme', [SettingController::class, 'theme'])->name('theme');
        Route::post('clear-cache', [SettingController::class, 'clearCache'])->name('clear-cache');
        Route::delete('delete-account/{id}', [SettingController::class, 'deleteAccount'])->name('delete-account');
    });
});
