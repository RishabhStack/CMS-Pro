<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasCompanyTrait;
use App\Traits\HasCreatorTrait;
use App\Traits\HasStatusTrait;

class User extends Authenticatable
{
    use SoftDeletes, HasApiTokens, HasFactory, Notifiable, HasCompanyTrait, HasCreatorTrait, HasStatusTrait;

    protected $fillable = [
        'company_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'avatar',
        'status',
        'language',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user')
            ->withTimestamps();
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function hasRole(string|array $role): bool
    {
        $roles = is_array($role) ? $role : [$role];
        return $this->roles()->where(function ($q) use ($roles) {
            foreach ($roles as $r) {
                $q->orWhere('slug', 'LIKE', $r . '-%');
            }
        })->exists();
    }

    public function hasPermission(string $permission): bool
    {
        return $this->roles()->whereHas('permissions', function ($q) use ($permission) {
            $q->where('slug', $permission);
        })->exists();
    }

    public function isOwner(): bool
    {
        return $this->hasRole('Owner');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('Admin');
    }
}
