<?php

namespace App\Actions\Ticket;

use App\Enums\TicketStatus;
use App\Events\Ticket\TicketStatusChanged;
use App\Models\Ticket;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Support\Facades\DB;

class ChangeStatusAction
{
    public function execute(Ticket $ticket, TicketStatus $newStatus, User $performedBy): Ticket
    {
        return DB::transaction(function () use ($ticket, $newStatus, $performedBy) {
            $oldStatus = $ticket->status;

            $ticket->update(['status' => $newStatus->value]);

            TicketStatusChanged::dispatch($ticket, $oldStatus, $newStatus->value, $performedBy);

            ActivityLogger::log(
                'updated',
                "Mengubah status ticket {$ticket->ticket_number} dari {$oldStatus} ke {$newStatus->value}",
                user: $performedBy,
                subjectType: Ticket::class,
                subjectId: $ticket->id,
                properties: ['field' => 'status', 'old_value' => $oldStatus, 'new_value' => $newStatus->value],
            );

            return $ticket->fresh();
        });
    }
}
