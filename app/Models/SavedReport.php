<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasCompanyTrait;
use App\Traits\HasCreatorTrait;

class SavedReport extends Model
{
    use SoftDeletes, HasCompanyTrait, HasCreatorTrait;

    protected $fillable = [
        'company_id',
        'name',
        'type',
        'filters',
        'created_by',
    ];

    protected $casts = [
        'filters' => 'json',
    ];
}
