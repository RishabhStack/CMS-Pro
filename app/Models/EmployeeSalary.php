<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasCompanyTrait;
use App\Traits\HasCreatorTrait;
use App\Traits\HasStatusTrait;

class EmployeeSalary extends Model
{
    use SoftDeletes, HasCompanyTrait, HasCreatorTrait, HasStatusTrait;

    protected $table = 'employee_salary';

    protected $fillable = [
        'company_id',
        'employee_id',
        'salary_component_id',
        'amount',
        'effective_date',
        'end_date',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'amount' => 'float',
        'effective_date' => 'date',
        'end_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function salaryComponent()
    {
        return $this->belongsTo(SalaryComponent::class);
    }
}
