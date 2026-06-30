<?php

namespace App\Actions\Ticket;

use App\Enums\TicketStatus;
use App\Events\Ticket\TicketAssigned;
use App\Models\Ticket;
use App\Models\User;
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

            if ($ticket->status === TicketStatus::OPEN->value) {
                $ticket->update(['status' => TicketStatus::ASSIGNED->value]);
            }

            TicketAssigned::dispatch($ticket, $assignedTo, $assignedBy);

            return $ticket->fresh();
        });
    }
}
