<?php

namespace App\Livewire\Ticket;

use App\Actions\Ticket\UpdateTicketAction;
use App\Models\Department;
use App\Models\Ticket;
use App\Models\TicketCategory;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Edit Ticket')]
class Edit extends Component
{
    public Ticket $ticket;

    public string $title = '';

    public string $description = '';

    public ?int $category_id = null;

    public ?int $department_id = null;

    public string $priority = 'MEDIUM';

    public function mount(): void
    {
        $this->authorize('update', $this->ticket);

        $this->title = $this->ticket->title;
        $this->description = $this->ticket->description;
        $this->department_id = $this->ticket->department_id;
        $this->category_id = $this->ticket->category_id;
        $this->priority = $this->ticket->priority;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'min:5', 'max:200'],
            'description' => ['required'],
            'category_id' => ['nullable', 'exists:ticket_categories,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'priority' => ['required', 'in:LOW,MEDIUM,HIGH,URGENT'],
        ];
    }

    public function confirmSave(): void
    {
        $this->authorize('update', $this->ticket);

        $this->validate();

        $this->dispatch('confirm-open', name: 'confirm-edit', title: __('Simpan Perubahan'), message: __('Yakin ingin menyimpan perubahan ticket ini?'), method: 'save', variant: 'primary', confirmLabel: __('Ya, Simpan'));
        $this->dispatch('modal-show', name: 'confirm-edit');
    }

    public function save(): void
    {
        $this->authorize('update', $this->ticket);

        $this->validate();

        $action = app(UpdateTicketAction::class);
        $action->execute($this->ticket, [
            'title' => $this->title,
            'description' => $this->description,
            'department_id' => $this->department_id,
            'category_id' => $this->category_id,
            'priority' => $this->priority,
        ], Auth::user());

        Flux::toast('Ticket berhasil diperbarui', variant: 'success');

        $this->redirect(route('tickets.show', $this->ticket), navigate: true);
    }

    public function render()
    {
        return view('livewire.ticket.edit', [
            'departments' => Department::pluck('name', 'id'),
            'categories' => TicketCategory::pluck('name', 'id'),
        ]);
    }
}
