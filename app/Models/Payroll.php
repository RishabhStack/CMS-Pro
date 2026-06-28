<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasCompanyTrait;
use App\Traits\HasCreatorTrait;
use App\Traits\HasStatusTrait;

class Payroll extends Model
{
    use SoftDeletes, Auditable, HasCompanyTrait, HasCreatorTrait, HasStatusTrait;

    protected $fillable = [
        'company_id',
        'employee_id',
        'payroll_period',
        'month',
        'year',
        'basic_salary',
        'total_earnings',
        'total_deductions',
        'net_salary',
        'total_days',
        'working_days',
        'present_days',
        'absent_days',
        'leave_days',
        'half_days',
        'status',
        'paid_at',
        'payment_method',
        'transaction_id',
        'notes',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'basic_salary' => 'float',
        'total_earnings' => 'float',
        'total_deductions' => 'float',
        'net_salary' => 'float',
        'total_days' => 'integer',
        'working_days' => 'integer',
        'present_days' => 'integer',
        'absent_days' => 'integer',
        'leave_days' => 'integer',
        'half_days' => 'integer',
        'paid_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
