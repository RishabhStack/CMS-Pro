<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCompanyTrait;

class CompanySetting extends Model
{
    use HasCompanyTrait;

    protected $fillable = [
        'company_id',
        'key',
        'value',
        'type',
        'created_by',
        'updated_by',
    ];
}
