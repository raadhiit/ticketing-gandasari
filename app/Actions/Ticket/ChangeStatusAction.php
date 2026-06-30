<?php

namespace App\Actions\Ticket;

use App\Enums\TicketStatus;
use App\Events\Ticket\TicketStatusChanged;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ChangeStatusAction
{
    public function execute(Ticket $ticket, TicketStatus $newStatus, User $performedBy): Ticket
    {
        return DB::transaction(function () use ($ticket, $newStatus, $performedBy) {
            $oldStatus = $ticket->status;

            $ticket->update(['status' => $newStatus->value]);

            TicketStatusChanged::dispatch($ticket, $oldStatus, $newStatus->value, $performedBy);

            return $ticket->fresh();
        });
    }
}
