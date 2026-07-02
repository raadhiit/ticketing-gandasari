<?php

namespace App\Actions\Ticket;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Events\Ticket\TicketCreated;
use App\Models\Ticket;
use App\Support\ActivityLogger;
use Illuminate\Support\Facades\DB;

class CreateTicketAction
{
    public function execute(array $data): Ticket
    {
        return DB::transaction(function () use ($data) {
            $ticket = Ticket::create([
                'ticket_number' => $this->generateTicketNumber(),
                'requester_id' => $data['requester_id'],
                'requester_name' => $data['requester_name'] ?? null,
                'department_id' => $data['department_id'] ?? null,
                'category_id' => $data['category_id'] ?? null,
                'title' => $data['title'],
                'description' => $data['description'],
                'priority' => $data['priority'] ?? TicketPriority::MEDIUM->value,
                'status' => TicketStatus::OPEN->value,
            ]);

            TicketCreated::dispatch($ticket);

            ActivityLogger::log(
                'created',
                "Membuat ticket {$ticket->ticket_number}",
                subjectType: Ticket::class,
                subjectId: $ticket->id,
                properties: ['title' => $ticket->title, 'status' => $ticket->status],
            );

            return $ticket;
        });
    }

    private function generateTicketNumber(): string
    {
        $prefix = 'TKT-'.now()->format('Ymd');

        $last = Ticket::where('ticket_number', 'like', $prefix.'-%')
            ->orderBy('ticket_number', 'desc')
            ->lockForUpdate()
            ->value('ticket_number');

        $sequence = $last ? (int) substr($last, -4) + 1 : 1;

        return $prefix.'-'.str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
