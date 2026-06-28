<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Announcement;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnnouncementPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Announcement $announcement): bool
    {
        return $user->company_id === $announcement->company_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function update(User $user, Announcement $announcement): bool
    {
        return $user->company_id === $announcement->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function delete(User $user, Announcement $announcement): bool
    {
        return $user->company_id === $announcement->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function restore(User $user, Announcement $announcement): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function forceDelete(User $user, Announcement $announcement): bool
    {
        return $user->hasRole('Owner');
    }
}
