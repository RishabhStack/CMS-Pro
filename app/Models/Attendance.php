<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasCompanyTrait;
use App\Traits\HasCreatorTrait;
use App\Traits\HasStatusTrait;

class Attendance extends Model
{
    use SoftDeletes, HasCompanyTrait, HasCreatorTrait, HasStatusTrait;

    protected $table = 'attendance';

    protected $fillable = [
        'company_id',
        'employee_id',
        'date',
        'clock_in',
        'clock_out',
        'break_start',
        'break_end',
        'total_break_minutes',
        'total_hours',
        'overtime_hours',
        'status',
        'notes',
        'ip_address',
        'location',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'date' => 'date',
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'break_start' => 'datetime',
        'break_end' => 'datetime',
        'total_break_minutes' => 'integer',
        'total_hours' => 'float',
        'overtime_hours' => 'float',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
