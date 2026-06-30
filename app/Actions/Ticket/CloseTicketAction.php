<?php

namespace App\Actions\Ticket;

use App\Enums\TicketStatus;
use App\Events\Ticket\TicketClosed;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CloseTicketAction
{
    public function execute(Ticket $ticket, User $closedBy): Ticket
    {
        return DB::transaction(function () use ($ticket, $closedBy) {
            $ticket->update([
                'status' => TicketStatus::CLOSED->value,
                'closed_at' => now(),
            ]);

            TicketClosed::dispatch($ticket, $closedBy);

            return $ticket->fresh();
        });
    }
}
