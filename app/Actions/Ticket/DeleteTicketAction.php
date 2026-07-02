<?php

namespace App\Actions\Ticket;

use App\Events\Ticket\TicketDeleted;
use App\Models\Ticket;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Support\Facades\DB;

class DeleteTicketAction
{
    public function execute(Ticket $ticket, User $deletedBy): void
    {
        DB::transaction(function () use ($ticket, $deletedBy) {
            $ticket->deleted_by = $deletedBy->id;
            $ticket->save();
            $ticket->delete();

            TicketDeleted::dispatch($ticket, $deletedBy);

            ActivityLogger::log(
                'deleted',
                "Menghapus ticket {$ticket->ticket_number}",
                user: $deletedBy,
                subjectType: Ticket::class,
                subjectId: $ticket->id,
                properties: ['title' => $ticket->title, 'status' => $ticket->status],
            );
        });
    }
}
