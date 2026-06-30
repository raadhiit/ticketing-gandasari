<?php

namespace App\Notifications\Ticket;

use App\Models\TicketComment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TicketCommentNotification extends Notification
{
    use Queueable;

    public function __construct(
        public TicketComment $comment,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $ticket = $this->comment->ticket;

        return [
            'ticket_id' => $ticket->id,
            'ticket_number' => $ticket->ticket_number,
            'title' => $ticket->title,
            'message' => "Komentar baru pada ticket {$ticket->ticket_number} oleh {$this->comment->user->name}.",
            'action_url' => route('tickets.show', $ticket),
        ];
    }
}
