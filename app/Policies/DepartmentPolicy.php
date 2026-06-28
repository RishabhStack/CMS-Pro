<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Department;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepartmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Department $department): bool
    {
        return $user->company_id === $department->company_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin') || $user->hasPermission('manage_departments');
    }

    public function update(User $user, Department $department): bool
    {
        return $user->company_id === $department->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin') || $user->hasPermission('manage_departments'));
    }

    public function delete(User $user, Department $department): bool
    {
        return $user->company_id === $department->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin') || $user->hasPermission('manage_departments'));
    }

    public function restore(User $user, Department $department): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function forceDelete(User $user, Department $department): bool
    {
        return $user->hasRole('Owner');
    }
}
