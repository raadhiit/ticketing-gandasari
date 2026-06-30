<?php

namespace App\Listeners;

use App\Events\Ticket\TicketAssigned;
use App\Events\Ticket\TicketClosed;
use App\Events\Ticket\TicketCommentAdded;
use App\Events\Ticket\TicketCreated;
use App\Events\Ticket\TicketReopened;
use App\Events\Ticket\TicketStatusChanged;

class TicketHistorySubscriber
{
    public function handleCreated(TicketCreated $event): void
    {
        $event->ticket->histories()->create([
            'action' => 'created',
            'field' => 'status',
            'new_value' => $event->ticket->status,
            'performed_by' => $event->ticket->requester_id,
            'created_at' => now(),
        ]);
    }

    public function handleAssigned(TicketAssigned $event): void
    {
        $event->ticket->histories()->create([
            'action' => 'assigned',
            'field' => 'assigned_to',
            'new_value' => $event->assignedTo->name,
            'performed_by' => $event->assignedBy->id,
            'created_at' => now(),
        ]);
    }

    public function handleStatusChanged(TicketStatusChanged $event): void
    {
        $event->ticket->histories()->create([
            'action' => 'status_changed',
            'field' => 'status',
            'old_value' => $event->oldStatus,
            'new_value' => $event->newStatus,
            'performed_by' => $event->performedBy->id,
            'created_at' => now(),
        ]);
    }

    public function handleClosed(TicketClosed $event): void
    {
        $event->ticket->histories()->create([
            'action' => 'closed',
            'field' => 'status',
            'old_value' => 'CLOSED',
            'new_value' => 'CLOSED',
            'performed_by' => $event->closedBy->id,
            'created_at' => now(),
        ]);
    }

    public function handleReopened(TicketReopened $event): void
    {
        $event->ticket->histories()->create([
            'action' => 'reopened',
            'field' => 'status',
            'old_value' => 'CLOSED',
            'new_value' => $event->ticket->status,
            'performed_by' => $event->reopenedBy->id,
            'created_at' => now(),
        ]);
    }

    public function handleCommentAdded(TicketCommentAdded $event): void
    {
        $event->comment->ticket->histories()->create([
            'action' => 'comment_added',
            'field' => 'comment',
            'new_value' => $event->comment->is_internal ? 'internal_note' : 'comment',
            'performed_by' => $event->comment->user_id,
            'created_at' => now(),
        ]);
    }

    public function subscribe(): array
    {
        return [
            TicketCreated::class => 'handleCreated',
            TicketAssigned::class => 'handleAssigned',
            TicketStatusChanged::class => 'handleStatusChanged',
            TicketClosed::class => 'handleClosed',
            TicketReopened::class => 'handleReopened',
            TicketCommentAdded::class => 'handleCommentAdded',
        ];
    }
}
