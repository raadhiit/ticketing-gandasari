<?php

namespace App\Livewire\Ticket;

use App\Actions\Ticket\CreateTicketAction;
use App\Actions\Ticket\UploadAttachmentAction;
use App\Models\Department;
use App\Models\Ticket;
use App\Models\TicketCategory;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Buat Ticket Baru')]
class Create extends Component
{
    use WithFileUploads;

    public string $title = '';

    public string $description = '';

    public ?int $category_id = null;

    public ?int $department_id = null;

    public string $priority = 'MEDIUM';

    public string $requester_name = '';

    public array $attachments = [];

    public function mount(): void
    {
        $this->requester_name = Auth::user()?->name ?? '';
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'min:5', 'max:200'],
            'description' => ['required'],
            'category_id' => ['nullable', 'exists:ticket_categories,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'priority' => ['required', 'in:LOW,MEDIUM,HIGH,URGENT'],
            'requester_name' => ['nullable', 'string', 'max:100'],
            'attachments.*' => ['nullable', 'file', 'max:10240', 'mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,zip,rar,txt,csv'],
        ];
    }

    public function removeAttachment(int $index): void
    {
        unset($this->attachments[$index]);
        $this->attachments = array_values($this->attachments);
    }

    public function confirmSave(): void
    {
        $this->validate();

        $this->dispatch('confirm-open', name: 'confirm-create', title: __('Buat Ticket'), message: __('Yakin ingin mengirim ticket ini? Pastikan semua data sudah benar.'), method: 'save', variant: 'primary', confirmLabel: __('Ya'));
        $this->dispatch('modal-show', name: 'confirm-create');
    }

    public function save(): void
    {
        $this->authorize('create', Ticket::class);

        $this->validate();

        $action = app(CreateTicketAction::class);
        $ticket = $action->execute([
            'requester_id' => Auth::id(),
            'requester_name' => $this->requester_name,
            'department_id' => $this->department_id,
            'category_id' => $this->category_id,
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority,
        ]);

        foreach ($this->attachments as $file) {
            app(UploadAttachmentAction::class)->execute($ticket, $file, Auth::user());
        }

        Flux::toast('Ticket berhasil dibuat', variant: 'success');

        $this->redirect(route('tickets.show', $ticket), navigate: true);
    }

    public function render()
    {
        return view('livewire.ticket.create', [
            'departments' => Department::pluck('name', 'id'),
            'categories' => TicketCategory::pluck('name', 'id'),
        ]);
    }
}
