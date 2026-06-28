<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Payroll;
use Illuminate\Auth\Access\HandlesAuthorization;

class PayrollPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin') || ($user->employee !== null);
    }

    public function view(User $user, Payroll $payroll): bool
    {
        if ($user->hasRole('Owner') || $user->hasRole('Admin')) {
            return $user->company_id === $payroll->company_id;
        }
        return $user->employee && $payroll->employee_id === $user->employee->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function update(User $user, Payroll $payroll): bool
    {
        return $user->company_id === $payroll->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function delete(User $user, Payroll $payroll): bool
    {
        return $user->company_id === $payroll->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function process(User $user, Payroll $payroll): bool
    {
        return $user->company_id === $payroll->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function restore(User $user, Payroll $payroll): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function forceDelete(User $user, Payroll $payroll): bool
    {
        return $user->hasRole('Owner');
    }
}
