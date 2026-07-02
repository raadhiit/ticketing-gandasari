<?php

namespace App\Actions\Ticket;

use App\Enums\TicketStatus;
use App\Events\Ticket\TicketReopened;
use App\Models\Ticket;
use App\Models\User;
use App\Support\ActivityLogger;
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

            ActivityLogger::log(
                'updated',
                "Membuka kembali ticket {$ticket->ticket_number}",
                user: $reopenedBy,
                subjectType: Ticket::class,
                subjectId: $ticket->id,
                properties: ['field' => 'status', 'old_value' => 'CLOSED', 'new_value' => $ticket->status],
            );

            return $ticket->fresh();
        });
    }
}
