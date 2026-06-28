<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ShiftSwapRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShiftSwapRequestPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ShiftSwapRequest $request): bool
    {
        return $user->company_id === $request->company_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, ShiftSwapRequest $request): bool
    {
        return $user->company_id === $request->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function delete(User $user, ShiftSwapRequest $request): bool
    {
        return $user->company_id === $request->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }
}
