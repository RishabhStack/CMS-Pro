<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Company;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('Owner');
    }

    public function view(User $user, Company $company): bool
    {
        return $user->company_id === $company->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Owner');
    }

    public function update(User $user, Company $company): bool
    {
        return $user->company_id === $company->id && $user->hasRole('Owner');
    }

    public function delete(User $user, Company $company): bool
    {
        return $user->company_id === $company->id && $user->hasRole('Owner');
    }

    public function restore(User $user, Company $company): bool
    {
        return $user->hasRole('Owner');
    }

    public function forceDelete(User $user, Company $company): bool
    {
        return $user->hasRole('Owner');
    }
}
