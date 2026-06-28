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
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ShiftAssignmentController;
use App\Http\Controllers\ShiftSwapRequestController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TimesheetController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\OrgChartController;
use App\Http\Controllers\ExitManagementController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PerformanceReviewController;
use App\Http\Controllers\TravelRequestController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AuditLogController;

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

    // Expense Categories
    Route::prefix('expense-categories')->name('expense-categories.')->group(function () {
        Route::get('/', [ExpenseCategoryController::class, 'index'])->name('index');
        Route::get('list', [ExpenseCategoryController::class, 'list'])->name('list');
        Route::get('create', [ExpenseCategoryController::class, 'create'])->name('create');
        Route::get('{id}/edit', [ExpenseCategoryController::class, 'edit'])->name('edit');
        Route::post('/', [ExpenseCategoryController::class, 'store'])->name('store');
        Route::put('{id}', [ExpenseCategoryController::class, 'update'])->name('update');
        Route::delete('{id}', [ExpenseCategoryController::class, 'destroy'])->name('destroy');
    });

    // Expenses
    Route::prefix('expenses')->name('expenses.')->group(function () {
        Route::get('/', [ExpenseController::class, 'index'])->name('index');
        Route::get('list', [ExpenseController::class, 'list'])->name('list');
        Route::get('create', [ExpenseController::class, 'create'])->name('create');
        Route::get('{id}/edit', [ExpenseController::class, 'edit'])->name('edit');
        Route::get('{id}', [ExpenseController::class, 'show'])->name('show');
        Route::post('/', [ExpenseController::class, 'store'])->name('store');
        Route::put('{id}', [ExpenseController::class, 'update'])->name('update');
        Route::delete('{id}', [ExpenseController::class, 'destroy'])->name('destroy');
        Route::post('{id}/approve', [ExpenseController::class, 'approve'])->name('approve');
        Route::post('{id}/reject', [ExpenseController::class, 'reject'])->name('reject');
        Route::post('{id}/pay', [ExpenseController::class, 'pay'])->name('pay');
    });

    // Assets
    Route::prefix('assets')->name('assets.')->group(function () {
        Route::get('/', [AssetController::class, 'index'])->name('index');
        Route::get('list', [AssetController::class, 'list'])->name('list');
        Route::get('create', [AssetController::class, 'create'])->name('create');
        Route::get('{id}/edit', [AssetController::class, 'edit'])->name('edit');
        Route::get('{id}', [AssetController::class, 'show'])->name('show');
        Route::get('{id}/assign-form', [AssetController::class, 'assignForm'])->name('assign-form');
        Route::get('{id}/return-form', [AssetController::class, 'returnForm'])->name('return-form');
        Route::post('/', [AssetController::class, 'store'])->name('store');
        Route::put('{id}', [AssetController::class, 'update'])->name('update');
        Route::delete('{id}', [AssetController::class, 'destroy'])->name('destroy');
        Route::post('{id}/assign', [AssetController::class, 'assign'])->name('assign');
        Route::post('{id}/return', [AssetController::class, 'returnAsset'])->name('return');
    });

    // Calendar
    Route::prefix('calendar')->name('calendar.')->group(function () {
        Route::get('/', [CalendarController::class, 'index'])->name('index');
        Route::get('events', [CalendarController::class, 'events'])->name('events');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('attendance-trend', [ReportController::class, 'attendanceTrend'])->name('attendance-trend');
        Route::get('leave-trend', [ReportController::class, 'leaveTrend'])->name('leave-trend');
        Route::get('payroll-summary', [ReportController::class, 'payrollSummary'])->name('payroll-summary');
        Route::get('headcount', [ReportController::class, 'headcount'])->name('headcount');
        Route::get('turnover-rate', [ReportController::class, 'turnoverRate'])->name('turnover-rate');
        Route::post('save', [ReportController::class, 'saveReport'])->name('save');
        Route::get('saved', [ReportController::class, 'savedReports'])->name('saved');
    });

    // Org Chart
    Route::prefix('org-chart')->name('orgchart.')->group(function () {
        Route::get('/', [OrgChartController::class, 'index'])->name('index');
        Route::get('data', [OrgChartController::class, 'data'])->name('data');
    });

    // Help
    Route::get('help', [HelpController::class, 'index'])->name('help');

    // Audit Logs
    Route::prefix('audit-logs')->name('audit-logs.')->group(function () {
        Route::get('/', [AuditLogController::class, 'index'])->name('index');
        Route::get('list', [AuditLogController::class, 'list'])->name('list');
    });

    // Exit Management
    Route::prefix('exit-management')->name('exit-management.')->group(function () {
        Route::get('/', [ExitManagementController::class, 'index'])->name('index');
        Route::get('list', [ExitManagementController::class, 'list'])->name('list');
        Route::get('{id}/edit', [ExitManagementController::class, 'edit'])->name('edit');
        Route::get('{id}', [ExitManagementController::class, 'show'])->name('show');
        Route::post('/', [ExitManagementController::class, 'store'])->name('store');
        Route::put('{id}', [ExitManagementController::class, 'update'])->name('update');
        Route::delete('{id}', [ExitManagementController::class, 'destroy'])->name('destroy');
        Route::post('{id}/approve', [ExitManagementController::class, 'approve'])->name('approve');
        Route::post('{id}/reject', [ExitManagementController::class, 'reject'])->name('reject');
        Route::post('clear-item/{id}', [ExitManagementController::class, 'clearItem'])->name('clear-item');
        Route::post('{id}/interview', [ExitManagementController::class, 'saveInterview'])->name('save-interview');
    });

    // Tickets
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::get('list', [TicketController::class, 'list'])->name('list');
        Route::get('{id}', [TicketController::class, 'show'])->name('show');
        Route::post('/', [TicketController::class, 'store'])->name('store');
        Route::put('{id}', [TicketController::class, 'update'])->name('update');
        Route::delete('{id}', [TicketController::class, 'destroy'])->name('destroy');
        Route::post('{id}/assign', [TicketController::class, 'assign'])->name('assign');
        Route::post('{id}/comment', [TicketController::class, 'addComment'])->name('add-comment');
        Route::post('{id}/resolve', [TicketController::class, 'resolve'])->name('resolve');
        Route::post('{id}/close', [TicketController::class, 'close'])->name('close');
        Route::post('{id}/reopen', [TicketController::class, 'reopen'])->name('reopen');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::match(['post', 'put'], 'update', [SettingController::class, 'update'])->name('update');
        Route::post('theme', [SettingController::class, 'theme'])->name('theme');
        Route::post('clear-cache', [SettingController::class, 'clearCache'])->name('clear-cache');
        Route::delete('delete-account/{id}', [SettingController::class, 'deleteAccount'])->name('delete-account');
    });

    // Performance Reviews
    Route::prefix('performance-reviews')->name('performance-reviews.')->group(function () {
        Route::get('/', [PerformanceReviewController::class, 'index'])->name('index');
        Route::get('list', [PerformanceReviewController::class, 'list'])->name('list');
        Route::get('create', [PerformanceReviewController::class, 'create'])->name('create');
        Route::get('{id}/edit', [PerformanceReviewController::class, 'edit'])->name('edit');
        Route::get('{id}', [PerformanceReviewController::class, 'show'])->name('show');
        Route::post('/', [PerformanceReviewController::class, 'store'])->name('store');
        Route::put('{id}', [PerformanceReviewController::class, 'update'])->name('update');
        Route::delete('{id}', [PerformanceReviewController::class, 'destroy'])->name('destroy');
        Route::post('{id}/goals', [PerformanceReviewController::class, 'goals'])->name('goals.store');
        Route::put('{id}/goals', [PerformanceReviewController::class, 'goals'])->name('goals.update');
        Route::delete('{id}/goals', [PerformanceReviewController::class, 'goals'])->name('goals.destroy');
        Route::post('{id}/submit', [PerformanceReviewController::class, 'submitForReview'])->name('submit');
        Route::post('{id}/complete', [PerformanceReviewController::class, 'completeReview'])->name('complete');
    });

    // Travel Requests
    Route::prefix('travel-requests')->name('travel-requests.')->group(function () {
        Route::get('/', [TravelRequestController::class, 'index'])->name('index');
        Route::get('list', [TravelRequestController::class, 'list'])->name('list');
        Route::get('create', [TravelRequestController::class, 'create'])->name('create');
        Route::get('{id}/edit', [TravelRequestController::class, 'edit'])->name('edit');
        Route::get('{id}', [TravelRequestController::class, 'show'])->name('show');
        Route::post('/', [TravelRequestController::class, 'store'])->name('store');
        Route::put('{id}', [TravelRequestController::class, 'update'])->name('update');
        Route::delete('{id}', [TravelRequestController::class, 'destroy'])->name('destroy');
        Route::post('{id}/approve', [TravelRequestController::class, 'approve'])->name('approve');
        Route::post('{id}/reject', [TravelRequestController::class, 'reject'])->name('reject');
        Route::post('{id}/submit', [TravelRequestController::class, 'submit'])->name('submit');
    });

    // Shifts
    Route::prefix('shifts')->name('shifts.')->group(function () {
        Route::get('/', [ShiftController::class, 'index'])->name('index');
        Route::get('list', [ShiftController::class, 'list'])->name('list');
        Route::get('create', [ShiftController::class, 'create'])->name('create');
        Route::get('{id}/edit', [ShiftController::class, 'edit'])->name('edit');
        Route::get('{id}', [ShiftController::class, 'show'])->name('show');
        Route::post('/', [ShiftController::class, 'store'])->name('store');
        Route::put('{id}', [ShiftController::class, 'update'])->name('update');
        Route::delete('{id}', [ShiftController::class, 'destroy'])->name('destroy');
    });

    // Shift Assignments
    Route::prefix('shift-assignments')->name('shift-assignments.')->group(function () {
        Route::get('/', [ShiftAssignmentController::class, 'index'])->name('index');
        Route::get('list', [ShiftAssignmentController::class, 'list'])->name('list');
        Route::get('{id}', [ShiftAssignmentController::class, 'show'])->name('show');
        Route::post('/', [ShiftAssignmentController::class, 'store'])->name('store');
        Route::post('bulk-store', [ShiftAssignmentController::class, 'bulkStore'])->name('bulk-store');
        Route::put('{id}', [ShiftAssignmentController::class, 'update'])->name('update');
        Route::delete('{id}', [ShiftAssignmentController::class, 'destroy'])->name('destroy');
    });

    // Shift Swap Requests
    Route::prefix('shift-swaps')->name('shift-swaps.')->group(function () {
        Route::get('/', [ShiftSwapRequestController::class, 'index'])->name('index');
        Route::get('list', [ShiftSwapRequestController::class, 'list'])->name('list');
        Route::get('create', [ShiftSwapRequestController::class, 'create'])->name('create');
        Route::post('/', [ShiftSwapRequestController::class, 'store'])->name('store');
        Route::post('{id}/approve', [ShiftSwapRequestController::class, 'approve'])->name('approve');
        Route::post('{id}/reject', [ShiftSwapRequestController::class, 'reject'])->name('reject');
        Route::delete('{id}', [ShiftSwapRequestController::class, 'destroy'])->name('destroy');
    });

    // Projects
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('index');
        Route::get('list', [ProjectController::class, 'list'])->name('list');
        Route::get('create', [ProjectController::class, 'create'])->name('create');
        Route::get('{id}/edit', [ProjectController::class, 'edit'])->name('edit');
        Route::get('{id}', [ProjectController::class, 'show'])->name('show');
        Route::post('/', [ProjectController::class, 'store'])->name('store');
        Route::put('{id}', [ProjectController::class, 'update'])->name('update');
        Route::delete('{id}', [ProjectController::class, 'destroy'])->name('destroy');
    });

    // Timesheets
    Route::prefix('timesheets')->name('timesheets.')->group(function () {
        Route::get('/', [TimesheetController::class, 'index'])->name('index');
        Route::get('list', [TimesheetController::class, 'list'])->name('list');
        Route::get('create', [TimesheetController::class, 'create'])->name('create');
        Route::get('{id}/edit', [TimesheetController::class, 'edit'])->name('edit');
        Route::get('{id}', [TimesheetController::class, 'show'])->name('show');
        Route::post('/', [TimesheetController::class, 'store'])->name('store');
        Route::put('{id}', [TimesheetController::class, 'update'])->name('update');
        Route::delete('{id}', [TimesheetController::class, 'destroy'])->name('destroy');
        Route::post('{id}/approve', [TimesheetController::class, 'approve'])->name('approve');
        Route::post('{id}/reject', [TimesheetController::class, 'reject'])->name('reject');
    });
});
