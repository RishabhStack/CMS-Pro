<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasCompanyTrait;
use App\Traits\HasCreatorTrait;
use App\Traits\HasStatusTrait;

class Employee extends Model
{
    use SoftDeletes, HasCompanyTrait, HasCreatorTrait, HasStatusTrait;

    protected $fillable = [
        'company_id',
        'user_id',
        'employee_code',
        'department_id',
        'designation_id',
        'status_id',
        'reporting_to_id',
        'joining_date',
        'confirmation_date',
        'exit_date',
        'employment_type',
        'work_shift',
        'work_location',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'notes',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = ['full_name', 'email'];

    protected $casts = [
        'joining_date' => 'date',
        'confirmation_date' => 'date',
        'exit_date' => 'date',
    ];

    public function getFullNameAttribute(): string
    {
        return $this->user?->first_name . ' ' . $this->user?->last_name;
    }

    public function getEmailAttribute(): ?string
    {
        return $this->user?->email;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function status()
    {
        return $this->belongsTo(EmployeeStatus::class, 'status_id');
    }

    public function reportingTo()
    {
        return $this->belongsTo(Employee::class, 'reporting_to_id');
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function salaries()
    {
        return $this->hasMany(EmployeeSalary::class);
    }
}
