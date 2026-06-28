<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasCompanyTrait;

class Timesheet extends Model
{
    use SoftDeletes, Auditable, HasCompanyTrait;

    protected $fillable = [
        'company_id',
        'employee_id',
        'project_id',
        'date',
        'task_name',
        'description',
        'start_time',
        'end_time',
        'total_hours',
        'is_billable',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'total_hours' => 'decimal:2',
        'is_billable' => 'bool',
        'approved_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
