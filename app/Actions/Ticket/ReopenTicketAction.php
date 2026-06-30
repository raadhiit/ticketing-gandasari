<?php

namespace App\Actions\Ticket;

use App\Enums\TicketStatus;
use App\Events\Ticket\TicketReopened;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReopenTicketAction
{
    public function execute(Ticket $ticket, User $reopenedBy): Ticket
    {
        return DB::transaction(function () use ($ticket, $reopenedBy) {
            $ticket->update([
                'status' => TicketStatus::OPEN->value,
                'closed_at' => null,
            ]);

            TicketReopened::dispatch($ticket, $reopenedBy);

            return $ticket->fresh();
        });
    }
}
