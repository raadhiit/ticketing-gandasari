<?php

namespace App\Actions\Ticket;

use App\Enums\TicketPriority;
use App\Events\Ticket\TicketUpdated;
use App\Models\Ticket;
use App\Models\User;
use App\Support\ActivityLogger;
use App\Support\CleanHtml;
use Illuminate\Support\Facades\DB;

class UpdateTicketAction
{
    public function execute(Ticket $ticket, array $data, User $performedBy): Ticket
    {
        return DB::transaction(function () use ($ticket, $data, $performedBy) {
            $allowed = ['title', 'description', 'department_id', 'category_id', 'priority'];
            $changedFields = [];

            foreach ($allowed as $field) {
                if (! array_key_exists($field, $data)) {
                    continue;
                }

                $oldValue = $ticket->{$field};
                $newValue = $data[$field];

                if ($field === 'priority' && $newValue instanceof TicketPriority) {
                    $newValue = $newValue->value;
                }

                if ($field === 'description') {
                    $newValue = CleanHtml::clean($newValue);
                }

                if ((string) $oldValue !== (string) $newValue) {
                    $changedFields[] = [
                        'field' => $field,
                        'old_value' => $oldValue,
                        'new_value' => $newValue,
                    ];
                }
            }

            if (empty($changedFields)) {
                return $ticket;
            }

            $updateData = [];

            foreach ($changedFields as $change) {
                $updateData[$change['field']] = $change['new_value'];
            }

            $ticket->update($updateData);

            TicketUpdated::dispatch($ticket, $changedFields, $performedBy);

            ActivityLogger::log(
                'updated',
                "Memperbarui ticket {$ticket->ticket_number}",
                user: $performedBy,
                subjectType: Ticket::class,
                subjectId: $ticket->id,
                properties: ['changed_fields' => $changedFields],
            );

            return $ticket->fresh();
        });
    }
}