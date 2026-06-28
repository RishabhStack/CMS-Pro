<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Document;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Document $document): bool
    {
        if ($user->hasRole('Owner') || $user->hasRole('Admin')) {
            return $user->company_id === $document->company_id;
        }
        return $user->employee && $document->employee_id === $user->employee->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Document $document): bool
    {
        if ($user->hasRole('Owner') || $user->hasRole('Admin')) {
            return $user->company_id === $document->company_id;
        }
        return $user->employee && $document->employee_id === $user->employee->id;
    }

    public function delete(User $user, Document $document): bool
    {
        if ($user->hasRole('Owner') || $user->hasRole('Admin')) {
            return $user->company_id === $document->company_id;
        }
        return $user->employee && $document->employee_id === $user->employee->id;
    }

    public function restore(User $user, Document $document): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function forceDelete(User $user, Document $document): bool
    {
        return $user->hasRole('Owner');
    }
}
