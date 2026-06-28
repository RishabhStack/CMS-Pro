<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function update(User $user, Role $role): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function restore(User $user, Role $role): bool
    {
        return $user->hasRole('Owner');
    }

    public function forceDelete(User $user, Role $role): bool
    {
        return $user->hasRole('Owner');
    }
}
