<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasCompanyTrait;
use App\Traits\HasCreatorTrait;

class Resignation extends Model
{
    use SoftDeletes, Auditable, HasCompanyTrait, HasCreatorTrait;

    protected $fillable = [
        'company_id',
        'employee_id',
        'notice_date',
        'last_working_date',
        'reason',
        'reason_category',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'notice_period_days',
        'accrued_leave_payout',
        'created_by',
    ];

    protected $casts = [
        'notice_date' => 'date',
        'last_working_date' => 'date',
        'approved_at' => 'datetime',
        'accrued_leave_payout' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function clearanceItems()
    {
        return $this->hasMany(ClearanceChecklistItem::class);
    }

    public function exitInterview()
    {
        return $this->hasOne(ExitInterview::class);
    }
}
