<?php

namespace App\Actions\Ticket;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Events\Ticket\TicketCreated;
use App\Models\Ticket;
use App\Support\ActivityLogger;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use App\Support\CleanHtml;

class CreateTicketAction
{
    public function execute(array $data): Ticket
    {
        $maxAttempts = 5;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                return DB::transaction(function () use ($data) {
                    $ticketNumber = $this->generateTicketNumber();

                    $ticket = Ticket::create([
                        'ticket_number' => $ticketNumber,
                        'requester_id' => $data['requester_id'],
                        'requester_name' => $data['requester_name'] ?? null,
                        'department_id' => $data['department_id'] ?? null,
                        'category_id' => $data['category_id'] ?? null,
                        'title' => $data['title'],
                        // 'description' => $data['description'],
                        'description' => CleanHtml::clean($data['description']),
                        'priority' => $data['priority'] ?? TicketPriority::MEDIUM->value,
                        'status' => TicketStatus::OPEN->value,
                    ]);

                    TicketCreated::dispatch($ticket);

                    ActivityLogger::log(
                        'created',
                        "Membuat ticket {$ticket->ticket_number}",
                        subjectType: Ticket::class,
                        subjectId: $ticket->id,
                        properties: [
                            'title' => $ticket->title,
                            'status' => $ticket->status,
                        ],
                    );

                    return $ticket;
                });
            } catch (QueryException $e) {
                if (! $this->isDuplicateTicketNumberError($e)) {
                    throw $e;
                }

                if ($attempt === $maxAttempts) {
                    throw $e;
                }

                usleep(100000);
            }
        }

        throw new RuntimeException('Gagal membuat ticket number unik.');
    }

    private function generateTicketNumber(): string
    {
        $prefix = 'TKT-' . now()->format('Ymd');

        $last = Ticket::withTrashed()
            ->where('ticket_number', 'like', $prefix . '-%')
            ->orderByDesc('ticket_number')
            ->lockForUpdate()
            ->value('ticket_number');

        $sequence = $last ? (int) substr($last, -4) + 1 : 1;

        return $prefix . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    private function isDuplicateTicketNumberError(QueryException $e): bool
    {
        return ($e->errorInfo[1] ?? null) === 1062;
    }
}