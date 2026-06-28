<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Leave;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeavePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Leave $leave): bool
    {
        if ($user->hasRole('Owner') || $user->hasRole('Admin')) {
            return $user->company_id === $leave->company_id;
        }
        return $user->employee && $leave->employee_id === $user->employee->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function approve(User $user, Leave $leave): bool
    {
        return $user->company_id === $leave->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin') || $user->hasRole('Manager'));
    }

    public function reject(User $user, Leave $leave): bool
    {
        return $user->company_id === $leave->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin') || $user->hasRole('Manager'));
    }

    public function update(User $user, Leave $leave): bool
    {
        return $user->company_id === $leave->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function delete(User $user, Leave $leave): bool
    {
        return $user->company_id === $leave->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function restore(User $user, Leave $leave): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function forceDelete(User $user, Leave $leave): bool
    {
        return $user->hasRole('Owner');
    }
}
