<?php

namespace App\Livewire\Ticket;

use App\Actions\Ticket\AddCommentAction;
use App\Actions\Ticket\AssignTicketAction;
use App\Actions\Ticket\ChangeStatusAction;
use App\Actions\Ticket\CloseTicketAction;
use App\Actions\Ticket\DeleteTicketAction;
use App\Actions\Ticket\ReopenTicketAction;
use App\Actions\Ticket\UploadAttachmentAction;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\User;
use Carbon\CarbonImmutable;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Detail Ticket')]
class Show extends Component
{
    use WithFileUploads;

    public Ticket $ticket;

    public string $comment = '';

    public bool $isInternal = false;

    public ?int $assignedUserId = null;

    public ?CarbonImmutable $lastCommentCheck = null;

    public bool $hasNewComments = false;

    public $attachment;

    public ?string $previewUrl = null;

    public ?string $previewName = null;

    protected $listeners = ['$refresh'];

    public function rules(): array
    {
        return [
            'attachment' => ['nullable', 'file', 'max:10240', 'mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,zip,rar,txt,csv'],
        ];
    }

    public function mount(): void
    {
        $this->lastCommentCheck = now();
    }

    public function checkNewComments(): void
    {
        $exists = $this->ticket->comments()
            ->where('created_at', '>', $this->lastCommentCheck)
            ->exists();

        if ($exists) {
            $this->lastCommentCheck = now();
            $this->hasNewComments = true;
            $this->dispatch('$refresh');
        }
    }

    public function addComment(): void
    {
        $this->authorize('comment', $this->ticket);

        $this->validate(['comment' => ['required', 'min:1']]);

        $action = app(AddCommentAction::class);
        $action->execute($this->ticket, [
            'comment' => $this->comment,
            'is_internal' => $this->isInternal,
        ], Auth::user());

        $this->comment = '';
        $this->isInternal = false;

        Flux::toast('Komentar ditambahkan', variant: 'success');
    }

    public function uploadAttachment(): void
    {
        $this->authorize('update', $this->ticket);

        $this->validate(['attachment' => ['required', 'file', 'max:10240', 'mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,zip,rar,txt,csv']]);

        $action = app(UploadAttachmentAction::class);
        $action->execute($this->ticket, $this->attachment, Auth::user());

        $this->attachment = null;

        Flux::toast('File berhasil diupload', variant: 'success');
    }

    public function delete(): void
    {
        $this->authorize('delete', $this->ticket);

        app(DeleteTicketAction::class)->execute($this->ticket, Auth::user());

        Flux::toast('Ticket berhasil dihapus', variant: 'success');

        $this->redirect(route('tickets.index'), navigate: true);
    }

    public function deleteAttachment(int $attachmentId): void
    {
        $this->authorize('update', $this->ticket);

        $attachment = TicketAttachment::findOrFail($attachmentId);

        Storage::disk('public')->delete($attachment->path);

        $attachment->delete();

        Flux::toast('Lampiran berhasil dihapus', variant: 'success');
    }

    public function previewAttachment(int $attachmentId): void
    {
        $attachment = TicketAttachment::findOrFail($attachmentId);

        $this->previewUrl = asset('storage/' . $attachment->path);
        $this->previewName = $attachment->filename;

        // $this->dispatch('modal-show', name: 'attachment-preview');
        Flux::modal('attachment-preview')->show();
    }

    public function assign(): void
    {
        $this->authorize('assign', $this->ticket);

        $this->validate(['assignedUserId' => ['required', 'exists:users,id']]);

        $agent = User::findOrFail($this->assignedUserId);

        $action = app(AssignTicketAction::class);
        $action->execute($this->ticket, $agent, Auth::user());

        $this->assignedUserId = null;

        Flux::toast('Ticket di-assign ke ' . $agent->name, variant: 'success');
    }

    public function changeStatus(string $status): void
    {
        if ($status === 'REOPEN') {
            $this->authorize('reopen', $this->ticket);

            app(ReopenTicketAction::class)->execute($this->ticket, Auth::user());

            Flux::toast('Ticket dibuka kembali', variant: 'success');

            return;
        }

        $newStatus = TicketStatus::tryFrom($status);

        if (! $newStatus) {
            Flux::toast('Status tidak valid', variant: 'danger');

            return;
        }

        $allowedTransitions = [
            'OPEN' => ['IN_PROGRESS'],
            'IN_PROGRESS' => ['RESOLVED'],
            'RESOLVED' => ['CLOSED'],
            'CLOSED' => [],
        ];

        $currentStatus = $this->ticket->status;

        if (! in_array($newStatus->value, $allowedTransitions[$currentStatus] ?? [], true)) {
            Flux::toast('Perubahan status tidak sesuai alur ticket', variant: 'warning');

            return;
        }

        $this->authorize('close', $this->ticket);

        if ($newStatus === TicketStatus::CLOSED) {
            app(CloseTicketAction::class)->execute($this->ticket, Auth::user());

            Flux::toast('Ticket ditutup', variant: 'success');

            return;
        }

        app(ChangeStatusAction::class)->execute($this->ticket, $newStatus, Auth::user());

        Flux::toast('Status diubah ke ' . str_replace('_', ' ', $newStatus->value), variant: 'success');
    }

    public function render()
    {
        $agents = User::role('IT ERP')->with('department')->get();

        $comments = $this->ticket->comments()
            ->with('user')
            ->where(function ($q) {
                $q->where('is_internal', false);
                if (Auth::user()?->can('ticket.comment.internal')) {
                    $q->orWhere('is_internal', true);
                }
            })
            ->latest()
            ->get();

        $histories = $this->ticket->histories()
            ->with('performedBy')
            ->latest()
            ->get();

        $currentAssignment = $this->ticket->assignments()
            ->whereNull('unassigned_at')
            ->with('assignedTo')
            ->latest()
            ->first();

        $attachments = $this->ticket->attachments()
            ->with('uploadedBy')
            ->latest()
            ->get();

        return view('livewire.ticket.show', [
            'agents' => $agents,
            'comments' => $comments,
            'histories' => $histories,
            'currentAssignment' => $currentAssignment,
            'attachments' => $attachments,
        ]);
    }
}
