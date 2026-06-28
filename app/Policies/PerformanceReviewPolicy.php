<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PerformanceReview;
use Illuminate\Auth\Access\HandlesAuthorization;

class PerformanceReviewPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        if ($user->hasRole(['Owner', 'Admin'])) {
            return true;
        }
        return $user->employee !== null;
    }

    public function view(User $user, PerformanceReview $review): bool
    {
        if ($user->hasRole(['Owner', 'Admin'])) {
            return $user->company_id === $review->company_id;
        }
        return $user->employee && $user->employee->id === $review->employee_id;
    }

    public function create(User $user): bool
    {
        if ($user->hasRole(['Owner', 'Admin'])) {
            return true;
        }
        return $user->employee !== null;
    }

    public function update(User $user, PerformanceReview $review): bool
    {
        return $user->hasRole(['Owner', 'Admin']) && $user->company_id === $review->company_id;
    }

    public function delete(User $user, PerformanceReview $review): bool
    {
        return $user->hasRole(['Owner', 'Admin']) && $user->company_id === $review->company_id;
    }
}
