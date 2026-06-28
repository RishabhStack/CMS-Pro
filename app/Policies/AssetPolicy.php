<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Asset;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssetPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->employee !== null || $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function view(User $user, Asset $asset): bool
    {
        return $user->company_id === $asset->company_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function update(User $user, Asset $asset): bool
    {
        return $user->company_id === $asset->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function delete(User $user, Asset $asset): bool
    {
        return $user->company_id === $asset->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function assign(User $user, Asset $asset): bool
    {
        return $user->company_id === $asset->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function returnAsset(User $user, Asset $asset): bool
    {
        return $user->company_id === $asset->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }
}
