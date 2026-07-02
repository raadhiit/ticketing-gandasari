<?php

namespace App\Actions\Ticket;

use App\Events\Ticket\TicketAssigned;
use App\Models\Ticket;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Support\Facades\DB;

class AssignTicketAction
{
    public function execute(Ticket $ticket, User $assignedTo, User $assignedBy): Ticket
    {
        return DB::transaction(function () use ($ticket, $assignedTo, $assignedBy) {
            $ticket->assignments()->create([
                'assigned_to' => $assignedTo->id,
                'assigned_by' => $assignedBy->id,
                'assigned_at' => now(),
            ]);

            TicketAssigned::dispatch($ticket, $assignedTo, $assignedBy);

            ActivityLogger::log(
                'updated',
                "Assign ticket {$ticket->ticket_number} ke {$assignedTo->name}",
                user: $assignedBy,
                subjectType: Ticket::class,
                subjectId: $ticket->id,
                properties: [
                    'field' => 'assigned_to',
                    'new_value' => $assignedTo->name,
                ],
            );

            return $ticket->fresh();
        });
    }
}