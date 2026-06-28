<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
use App\Traits\HasCompanyTrait;
use App\Traits\HasStatusTrait;

class Project extends Model
{
    use HasCompanyTrait, HasStatusTrait, Auditable;

    protected $fillable = [
        'company_id',
        'name',
        'slug',
        'description',
        'status',
    ];

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }
}
