<?php

namespace App\Events\Ticket;

use App\Models\Ticket;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;

class TicketCreated
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        public Ticket $ticket,
    ) {}
}
