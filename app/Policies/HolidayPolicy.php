<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Holiday;
use Illuminate\Auth\Access\HandlesAuthorization;

class HolidayPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Holiday $holiday): bool
    {
        return $user->company_id === $holiday->company_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function update(User $user, Holiday $holiday): bool
    {
        return $user->company_id === $holiday->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function delete(User $user, Holiday $holiday): bool
    {
        return $user->company_id === $holiday->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function restore(User $user, Holiday $holiday): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function forceDelete(User $user, Holiday $holiday): bool
    {
        return $user->hasRole('Owner');
    }
}
