<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasCompanyTrait;
use App\Traits\HasCreatorTrait;
use App\Traits\HasStatusTrait;

class EmployeeStatus extends Model
{
    use SoftDeletes, HasCompanyTrait, HasCreatorTrait, HasStatusTrait;

    protected $fillable = [
        'company_id',
        'name',
        'slug',
        'color',
        'description',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'status_id');
    }
}
