<?php

namespace App\Actions\Ticket;

use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class UploadAttachmentAction
{
    public function execute(Ticket $ticket, UploadedFile $file, User $uploadedBy): TicketAttachment
    {
        return DB::transaction(function () use ($ticket, $file, $uploadedBy) {
            $path = $file->store('tickets/'.$ticket->id, 'public');

            $attachment = $ticket->attachments()->create([
                'uploaded_by' => $uploadedBy->id,
                'filename' => $file->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);

            return $attachment;
        });
    }
}
