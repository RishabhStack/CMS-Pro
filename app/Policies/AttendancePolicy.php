<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendancePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Attendance $attendance): bool
    {
        if ($user->hasRole('Owner') || $user->hasRole('Admin')) {
            return $user->company_id === $attendance->company_id;
        }
        return $user->employee && $attendance->employee_id === $user->employee->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Attendance $attendance): bool
    {
        return $user->company_id === $attendance->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin') || $user->hasRole('Manager'));
    }

    public function delete(User $user, Attendance $attendance): bool
    {
        return $user->company_id === $attendance->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function restore(User $user, Attendance $attendance): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function forceDelete(User $user, Attendance $attendance): bool
    {
        return $user->hasRole('Owner');
    }
}
