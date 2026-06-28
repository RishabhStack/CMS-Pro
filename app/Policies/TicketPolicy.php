<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->hasRole('Owner') || $user->hasRole('Admin')) {
            return $user->company_id === $ticket->company_id;
        }
        if ($ticket->assigned_to === $user->id) {
            return true;
        }
        return $user->employee && $ticket->employee_id === $user->employee->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Ticket $ticket): bool
    {
        return $user->company_id === $ticket->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->company_id === $ticket->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }

    public function assign(User $user, Ticket $ticket): bool
    {
        return $user->company_id === $ticket->company_id && ($user->hasRole('Owner') || $user->hasRole('Admin'));
    }
}
