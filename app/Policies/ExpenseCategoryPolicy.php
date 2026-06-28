<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ExpenseCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpenseCategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ExpenseCategory $category): bool
    {
        return $user->company_id === $category->company_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function update(User $user, ExpenseCategory $category): bool
    {
        return $user->company_id === $category->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function delete(User $user, ExpenseCategory $category): bool
    {
        return $user->company_id === $category->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }
}
