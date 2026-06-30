<?php

namespace App\Events\Ticket;

use App\Models\TicketComment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;

class TicketCommentAdded
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        public TicketComment $comment,
    ) {}
}
