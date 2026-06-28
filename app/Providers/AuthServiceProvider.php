<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\SalaryComponent::class => \App\Policies\SalaryComponentPolicy::class,
        \App\Models\Shift::class => \App\Policies\ShiftPolicy::class,
        \App\Models\ShiftAssignment::class => \App\Policies\ShiftAssignmentPolicy::class,
        \App\Models\ShiftSwapRequest::class => \App\Policies\ShiftSwapRequestPolicy::class,
        \App\Models\Timesheet::class => \App\Policies\TimesheetPolicy::class,
        \App\Models\Project::class => \App\Policies\ProjectPolicy::class,
        \App\Models\PerformanceReview::class => \App\Policies\PerformanceReviewPolicy::class,
        \App\Models\Expense::class => \App\Policies\ExpensePolicy::class,
        \App\Models\ExpenseCategory::class => \App\Policies\ExpenseCategoryPolicy::class,
        \App\Models\Asset::class => \App\Policies\AssetPolicy::class,
        \App\Models\TravelRequest::class => \App\Policies\TravelRequestPolicy::class,
        \App\Models\Resignation::class => \App\Policies\ResignationPolicy::class,
        \App\Models\Ticket::class => \App\Policies\TicketPolicy::class,
        \App\Models\AuditLog::class => \App\Policies\AuditLogPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
