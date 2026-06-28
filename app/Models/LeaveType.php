<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasCompanyTrait;
use App\Traits\HasCreatorTrait;
use App\Traits\HasStatusTrait;

class LeaveType extends Model
{
    use SoftDeletes, HasCompanyTrait, HasCreatorTrait, HasStatusTrait;

    protected $fillable = [
        'company_id',
        'name',
        'slug',
        'description',
        'days_per_year',
        'carry_forward',
        'max_carry_forward',
        'color',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'days_per_year' => 'integer',
        'carry_forward' => 'boolean',
        'max_carry_forward' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = \Illuminate\Support\Str::slug($model->name);
            }
        });
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }
}
