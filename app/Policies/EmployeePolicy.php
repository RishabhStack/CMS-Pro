<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Employee $employee): bool
    {
        return $user->company_id === $employee->company_id || $user->id === $employee->user_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin') || $user->hasPermission('manage_employees');
    }

    public function update(User $user, Employee $employee): bool
    {
        return $user->company_id === $employee->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin') || $user->hasPermission('manage_employees'));
    }

    public function delete(User $user, Employee $employee): bool
    {
        return $user->company_id === $employee->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function restore(User $user, Employee $employee): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function forceDelete(User $user, Employee $employee): bool
    {
        return $user->hasRole('Owner');
    }
}
