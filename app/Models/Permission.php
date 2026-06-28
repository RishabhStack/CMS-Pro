<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasCreatorTrait;

class Permission extends Model
{
    use SoftDeletes, HasCreatorTrait;

    protected $fillable = [
        'name',
        'slug',
        'group',
        'description',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role')
            ->withTimestamps();
    }
}
