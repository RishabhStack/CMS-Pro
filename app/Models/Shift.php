<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
use App\Traits\HasCompanyTrait;
use App\Traits\HasStatusTrait;

class Shift extends Model
{
    use HasCompanyTrait, HasStatusTrait, Auditable;

    protected $fillable = [
        'company_id',
        'name',
        'slug',
        'start_time',
        'end_time',
        'grace_minutes',
        'half_day_cutoff',
        'description',
        'color',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'half_day_cutoff' => 'datetime:H:i',
    ];

    public function assignments()
    {
        return $this->hasMany(ShiftAssignment::class);
    }
}
