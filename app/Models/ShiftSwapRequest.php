<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class ShiftSwapRequest extends Model
{
    use Auditable;
    protected $fillable = [
        'company_id',
        'from_employee_id',
        'to_employee_id',
        'shift_assignment_id',
        'date',
        'reason',
        'status',
        'responded_by',
        'responded_at',
    ];

    protected $casts = [
        'date' => 'date',
        'responded_at' => 'datetime',
    ];

    public function fromEmployee()
    {
        return $this->belongsTo(Employee::class, 'from_employee_id');
    }

    public function toEmployee()
    {
        return $this->belongsTo(Employee::class, 'to_employee_id');
    }

    public function shiftAssignment()
    {
        return $this->belongsTo(ShiftAssignment::class);
    }

    public function responder()
    {
        return $this->belongsTo(User::class, 'responded_by');
    }
}
