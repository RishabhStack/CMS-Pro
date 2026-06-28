<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Resignation;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResignationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Resignation $resignation): bool
    {
        if ($user->hasRole('Owner') || $user->hasRole('Admin')) {
            return $user->company_id === $resignation->company_id;
        }
        return $user->employee && $resignation->employee_id === $user->employee->id;
    }

    public function create(User $user): bool
    {
        return $user->employee !== null;
    }

    public function update(User $user, Resignation $resignation): bool
    {
        if ($user->hasRole('Owner') || $user->hasRole('Admin')) {
            return $user->company_id === $resignation->company_id;
        }
        return $user->employee && $resignation->employee_id === $user->employee->id && $resignation->status === 'pending';
    }

    public function delete(User $user, Resignation $resignation): bool
    {
        return $user->company_id === $resignation->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function approve(User $user, Resignation $resignation): bool
    {
        return $user->company_id === $resignation->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function reject(User $user, Resignation $resignation): bool
    {
        return $user->company_id === $resignation->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }
}
