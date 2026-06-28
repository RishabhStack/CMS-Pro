<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Timesheet;
use Illuminate\Auth\Access\HandlesAuthorization;

class TimesheetPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Timesheet $timesheet): bool
    {
        return $user->company_id === $timesheet->company_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Timesheet $timesheet): bool
    {
        return $user->company_id === $timesheet->company_id
            && $timesheet->status === 'draft'
            && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function delete(User $user, Timesheet $timesheet): bool
    {
        return $user->company_id === $timesheet->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function approve(User $user, Timesheet $timesheet): bool
    {
        return $user->company_id === $timesheet->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }
}
