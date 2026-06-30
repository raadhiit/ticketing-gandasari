<?php

namespace App\Events\Ticket;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;

class TicketAssigned
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        public Ticket $ticket,
        public User $assignedTo,
        public User $assignedBy,
    ) {}
}
