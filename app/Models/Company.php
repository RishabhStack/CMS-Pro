<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasCreatorTrait;
use App\Traits\HasStatusTrait;

class Company extends Model
{
    use SoftDeletes, HasCreatorTrait, HasStatusTrait;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'logo',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'timezone',
        'currency',
        'date_format',
        'time_format',
        'language',
        'website',
        'tax_number',
        'financial_year_start',
        'week_start_day',
        'rows_per_page',
        'theme_color',
        'dark_mode',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'dark_mode' => 'boolean',
        'financial_year_start' => 'date',
    ];

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function designations()
    {
        return $this->hasMany(Designation::class);
    }

    public function employeeStatuses()
    {
        return $this->hasMany(EmployeeStatus::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function leaveTypes()
    {
        return $this->hasMany(LeaveType::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function holidays()
    {
        return $this->hasMany(Holiday::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function salaryComponents()
    {
        return $this->hasMany(SalaryComponent::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function settings()
    {
        return $this->hasMany(CompanySetting::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }
}
