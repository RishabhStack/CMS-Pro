<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LeaveType;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeaveTypePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, LeaveType $leaveType): bool
    {
        return $user->company_id === $leaveType->company_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function update(User $user, LeaveType $leaveType): bool
    {
        return $user->company_id === $leaveType->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function delete(User $user, LeaveType $leaveType): bool
    {
        return $user->company_id === $leaveType->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function restore(User $user, LeaveType $leaveType): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function forceDelete(User $user, LeaveType $leaveType): bool
    {
        return $user->hasRole('Owner');
    }
}
