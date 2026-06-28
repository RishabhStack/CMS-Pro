<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasCompanyTrait;
use App\Traits\HasCreatorTrait;
use App\Traits\HasStatusTrait;

class Holiday extends Model
{
    use SoftDeletes, HasCompanyTrait, HasCreatorTrait, HasStatusTrait;

    protected $fillable = [
        'company_id',
        'name',
        'date',
        'year',
        'type',
        'description',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = ['day'];

    protected $casts = [
        'date' => 'date',
        'year' => 'integer',
    ];

    public function getDayAttribute(): ?string
    {
        return $this->date?->format('l');
    }
}
