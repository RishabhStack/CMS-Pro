<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\CompanyRepository::class,
            \App\Repositories\CompanyRepository::class
        );
        $this->app->bind(
            \App\Repositories\DepartmentRepository::class,
            \App\Repositories\DepartmentRepository::class
        );
        $this->app->bind(
            \App\Repositories\DesignationRepository::class,
            \App\Repositories\DesignationRepository::class
        );
        $this->app->bind(
            \App\Repositories\EmployeeRepository::class,
            \App\Repositories\EmployeeRepository::class
        );
        $this->app->bind(
            \App\Repositories\RoleRepository::class,
            \App\Repositories\RoleRepository::class
        );
        $this->app->bind(
            \App\Repositories\PermissionRepository::class,
            \App\Repositories\PermissionRepository::class
        );
        $this->app->bind(
            \App\Repositories\AttendanceRepository::class,
            \App\Repositories\AttendanceRepository::class
        );
        $this->app->bind(
            \App\Repositories\LeaveRepository::class,
            \App\Repositories\LeaveRepository::class
        );
        $this->app->bind(
            \App\Repositories\LeaveTypeRepository::class,
            \App\Repositories\LeaveTypeRepository::class
        );
        $this->app->bind(
            \App\Repositories\HolidayRepository::class,
            \App\Repositories\HolidayRepository::class
        );
        $this->app->bind(
            \App\Repositories\PayrollRepository::class,
            \App\Repositories\PayrollRepository::class
        );
        $this->app->bind(
            \App\Repositories\SalaryComponentRepository::class,
            \App\Repositories\SalaryComponentRepository::class
        );
        $this->app->bind(
            \App\Repositories\DocumentRepository::class,
            \App\Repositories\DocumentRepository::class
        );
        $this->app->bind(
            \App\Repositories\AnnouncementRepository::class,
            \App\Repositories\AnnouncementRepository::class
        );
        $this->app->bind(
            \App\Repositories\SettingRepository::class,
            \App\Repositories\SettingRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
