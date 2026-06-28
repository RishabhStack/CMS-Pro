<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Setting $setting): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }

    public function update(User $user, Setting $setting): bool
    {
        return $user->hasRole('Owner') || $user->hasRole('Admin');
    }
}
