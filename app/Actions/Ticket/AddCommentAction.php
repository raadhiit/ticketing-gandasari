<?php

namespace App\Actions\Ticket;

use App\Events\Ticket\TicketCommentAdded;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AddCommentAction
{
    public function execute(Ticket $ticket, array $data, User $user): TicketComment
    {
        return DB::transaction(function () use ($ticket, $data, $user) {
            $comment = $ticket->comments()->create([
                'user_id' => $user->id,
                'comment' => $data['comment'],
                'is_internal' => $data['is_internal'] ?? false,
            ]);

            TicketCommentAdded::dispatch($comment);

            return $comment;
        });
    }
}
