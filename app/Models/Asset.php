<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasCompanyTrait;

class Asset extends Model
{
    use SoftDeletes, Auditable, HasCompanyTrait;

    protected $fillable = [
        'company_id',
        'name',
        'type',
        'serial_number',
        'brand',
        'model',
        'purchase_date',
        'purchase_cost',
        'warranty_expiry',
        'status',
        'notes',
        'image_path',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_cost' => 'decimal:2',
        'warranty_expiry' => 'date',
    ];

    public function currentAssignment()
    {
        return $this->hasOne(AssetAssignment::class)->whereNull('returned_at');
    }

    public function assignments()
    {
        return $this->hasMany(AssetAssignment::class);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
