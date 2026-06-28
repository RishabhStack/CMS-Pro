<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TravelRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class TravelRequestPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->employee !== null;
    }

    public function view(User $user, TravelRequest $travelRequest): bool
    {
        if ($user->hasRole(['Owner', 'Admin'])) {
            return $user->company_id === $travelRequest->company_id;
        }
        return $user->employee && $user->employee->id === $travelRequest->employee_id;
    }

    public function create(User $user): bool
    {
        return $user->employee !== null;
    }

    public function update(User $user, TravelRequest $travelRequest): bool
    {
        return $user->hasRole(['Owner', 'Admin']) && $user->company_id === $travelRequest->company_id;
    }

    public function delete(User $user, TravelRequest $travelRequest): bool
    {
        return $user->hasRole(['Owner', 'Admin']) && $user->company_id === $travelRequest->company_id;
    }

    public function approve(User $user, TravelRequest $travelRequest): bool
    {
        return $user->hasRole(['Owner', 'Admin']) && $user->company_id === $travelRequest->company_id;
    }
}
