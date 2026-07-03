<?php

namespace App\Actions\Ticket;

use App\Events\Ticket\TicketCommentAdded;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Support\Facades\DB;
use App\Support\CleanHtml;

class AddCommentAction
{
    public function execute(Ticket $ticket, array $data, User $user): TicketComment
    {
        return DB::transaction(function () use ($ticket, $data, $user) {
            $comment = $ticket->comments()->create([
                'user_id' => $user->id,
                // 'comment' => $data['comment'],
                'comment' => CleanHtml::clean($data['comment']),
                'is_internal' => $data['is_internal'] ?? false,
            ]);

            TicketCommentAdded::dispatch($comment);

            ActivityLogger::log(
                'updated',
                $data['is_internal']
                    ? "Menambahkan catatan internal pada ticket {$ticket->ticket_number}"
                    : "Menambahkan komentar pada ticket {$ticket->ticket_number}",
                user: $user,
                subjectType: Ticket::class,
                subjectId: $ticket->id,
                properties: ['field' => 'comment', 'is_internal' => $data['is_internal'] ?? false],
            );

            return $comment;
        });
    }
}
