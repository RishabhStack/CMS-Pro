<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Expense;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpensePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->employee !== null || $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function view(User $user, Expense $expense): bool
    {
        if ($user->hasRole('Owner') || $user->hasRole('Admin')) {
            return $user->company_id === $expense->company_id;
        }
        return $user->employee && $expense->employee_id === $user->employee->id;
    }

    public function create(User $user): bool
    {
        return $user->employee !== null || $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function update(User $user, Expense $expense): bool
    {
        if ($user->hasRole('Owner') || $user->hasRole('Admin')) {
            return $user->company_id === $expense->company_id;
        }
        return $user->employee && $expense->employee_id === $user->employee->id && in_array($expense->status, ['draft', 'pending']);
    }

    public function delete(User $user, Expense $expense): bool
    {
        return $user->company_id === $expense->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function approve(User $user, Expense $expense): bool
    {
        return $user->company_id === $expense->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function reject(User $user, Expense $expense): bool
    {
        return $user->company_id === $expense->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function pay(User $user, Expense $expense): bool
    {
        return $user->company_id === $expense->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }
}
