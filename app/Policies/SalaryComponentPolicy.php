<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SalaryComponent;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalaryComponentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, SalaryComponent $salaryComponent): bool
    {
        return $user->company_id === $salaryComponent->company_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin') || $user->hasPermission('manage_salary_components');
    }

    public function update(User $user, SalaryComponent $salaryComponent): bool
    {
        return $user->company_id === $salaryComponent->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin') || $user->hasPermission('manage_salary_components'));
    }

    public function delete(User $user, SalaryComponent $salaryComponent): bool
    {
        return $user->company_id === $salaryComponent->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin') || $user->hasPermission('manage_salary_components'));
    }

    public function restore(User $user, SalaryComponent $salaryComponent): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function forceDelete(User $user, SalaryComponent $salaryComponent): bool
    {
        return $user->hasRole('Owner');
    }
}
