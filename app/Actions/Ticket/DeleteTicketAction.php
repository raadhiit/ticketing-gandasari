<?php

namespace App\Actions\Ticket;

use App\Events\Ticket\TicketDeleted;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DeleteTicketAction
{
    public function execute(Ticket $ticket, User $deletedBy): void
    {
        DB::transaction(function () use ($ticket, $deletedBy) {
            $ticket->delete();

            TicketDeleted::dispatch($ticket, $deletedBy);
        });
    }
}
