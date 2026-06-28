<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Designation;
use Illuminate\Auth\Access\HandlesAuthorization;

class DesignationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Designation $designation): bool
    {
        return $user->company_id === $designation->company_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin') || $user->hasPermission('manage_designations');
    }

    public function update(User $user, Designation $designation): bool
    {
        return $user->company_id === $designation->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin') || $user->hasPermission('manage_designations'));
    }

    public function delete(User $user, Designation $designation): bool
    {
        return $user->company_id === $designation->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin') || $user->hasPermission('manage_designations'));
    }

    public function restore(User $user, Designation $designation): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function forceDelete(User $user, Designation $designation): bool
    {
        return $user->hasRole('Owner');
    }
}
