<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ShiftAssignment;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShiftAssignmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ShiftAssignment $assignment): bool
    {
        return $user->company_id === $assignment->employee?->company_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function update(User $user, ShiftAssignment $assignment): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function delete(User $user, ShiftAssignment $assignment): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }
}
