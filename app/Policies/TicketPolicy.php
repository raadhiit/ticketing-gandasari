<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    private function isSupport(User $user): bool
    {
        return $user->hasAnyRole(['IT ERP', 'superadmin']);
    }

    public function viewAny(User $user): bool
    {
        return $user->can('ticket.view') || $user->can('ticket.create');
    }

    public function view(User $user, Ticket $ticket): bool
    {
        return $user->can('ticket.view') || $ticket->requester_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->can('ticket.create');
    }

    public function update(User $user, Ticket $ticket): bool
    {
        return $user->hasRole('User')
            && $ticket->requester_id === $user->id
            && $ticket->status === 'OPEN';
    }

    public function assign(User $user, Ticket $ticket): bool
    {
        return $this->isSupport($user)
            && $ticket->status !== 'CLOSED';
    }

    public function close(User $user, Ticket $ticket): bool
    {
        return $this->isSupport($user)
            && $ticket->status !== 'CLOSED';
    }

    public function reopen(User $user, Ticket $ticket): bool
    {
        return $this->isSupport($user)
            && $ticket->status === 'CLOSED';
    }

    public function comment(User $user, Ticket $ticket): bool
    {
        if (! $user->can('ticket.comment')) {
            return false;
        }

        return $user->can('ticket.comment.internal')
            || $ticket->requester_id === $user->id;
    }

    public function commentInternal(User $user, Ticket $ticket): bool
    {
        return $this->isSupport($user)
            && $user->can('ticket.comment.internal');
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return $this->isSupport($user);
    }
}
