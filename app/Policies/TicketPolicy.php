<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
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
        return $user->can('ticket.edit');
    }

    public function assign(User $user, Ticket $ticket): bool
    {
        return $user->can('ticket.assign') && $ticket->status !== 'CLOSED' && $ticket->status !== 'CANCELLED';
    }

    public function close(User $user, Ticket $ticket): bool
    {
        return $user->can('ticket.close') && ! in_array($ticket->status, ['CLOSED', 'CANCELLED']);
    }

    public function reopen(User $user, Ticket $ticket): bool
    {
        return $user->can('ticket.reopen') && $ticket->status === 'CLOSED';
    }

    public function comment(User $user, Ticket $ticket): bool
    {
        if (! $user->can('ticket.comment')) {
            return false;
        }

        return $user->can('ticket.comment.internal') || $ticket->requester_id === $user->id;
    }

    public function commentInternal(User $user, Ticket $ticket): bool
    {
        return $user->can('ticket.comment.internal');
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->can('ticket.delete');
    }
}
