<?php

namespace App\Listeners;

use App\Events\Ticket\TicketAssigned;
use App\Events\Ticket\TicketClosed;
use App\Events\Ticket\TicketCommentAdded;
use App\Events\Ticket\TicketCreated;
use App\Events\Ticket\TicketReopened;
use App\Events\Ticket\TicketStatusChanged;
use App\Models\User;
use App\Notifications\Ticket\TicketAssignedNotification;
use App\Notifications\Ticket\TicketCommentNotification;
use App\Notifications\Ticket\TicketCreatedNotification;
use App\Notifications\Ticket\TicketStatusNotification;

class NotificationSubscriber
{
    public function handleCreated(TicketCreated $event): void
    {
        $recipients = User::permission('ticket.assign')->get();

        foreach ($recipients as $recipient) {
            $recipient->notify(new TicketCreatedNotification($event->ticket));
        }
    }

    public function handleAssigned(TicketAssigned $event): void
    {
        $event->assignedTo->notify(
            new TicketAssignedNotification($event->ticket, $event->assignedBy)
        );
    }

    public function handleStatusChanged(TicketStatusChanged $event): void
    {
        $requester = $event->ticket->requester;
        if ($requester && $requester->id !== $event->performedBy->id) {
            $requester->notify(
                new TicketStatusNotification($event->ticket, $event->oldStatus, $event->newStatus, $event->performedBy)
            );
        }
    }

    public function handleClosed(TicketClosed $event): void
    {
        $requester = $event->ticket->requester;
        if ($requester && $requester->id !== $event->closedBy->id) {
            $requester->notify(
                new TicketStatusNotification($event->ticket, $event->ticket->status, 'CLOSED', $event->closedBy)
            );
        }
    }

    public function handleReopened(TicketReopened $event): void
    {
        $assignees = $event->ticket->activeAssignment?->assignedTo;
        if ($assignees && $assignees->id !== $event->reopenedBy->id) {
            $assignees->notify(
                new TicketStatusNotification($event->ticket, 'CLOSED', $event->ticket->status, $event->reopenedBy)
            );
        }
    }

    public function handleCommentAdded(TicketCommentAdded $event): void
    {
        $ticket = $event->comment->ticket;
        $commenter = $event->comment->user;

        $recipients = User::query()
            ->where(function ($q) use ($ticket) {
                $q->where('id', $ticket->requester_id)
                    ->orWhereIn('id', $ticket->activeAssignment()->select('assigned_to'));
            })
            ->where('id', '!=', $commenter->id)
            ->get();

        foreach ($recipients as $recipient) {
            $recipient->notify(new TicketCommentNotification($event->comment));
        }
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
