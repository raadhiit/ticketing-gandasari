<?php

namespace App\Livewire\Ticket;

use App\Actions\Ticket\CreateTicketAction;
use App\Models\Department;
use App\Models\Ticket;
use App\Models\TicketCategory;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Buat Ticket Baru')]
class Create extends Component
{
    public string $title = '';

    public string $description = '';

    public ?int $category_id = null;

    public ?int $department_id = null;

    public string $priority = 'MEDIUM';

    public function rules(): array
    {
        return [
            'title' => ['required', 'min:5', 'max:200'],
            'description' => ['required', 'min:10'],
            'category_id' => ['nullable', 'exists:ticket_categories,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'priority' => ['required', 'in:LOW,MEDIUM,HIGH,URGENT'],
        ];
    }

    public function save(): void
    {
        $this->authorize('create', Ticket::class);

        $this->validate();

        $action = app(CreateTicketAction::class);
        $ticket = $action->execute([
            'requester_id' => auth()->id(),
            'department_id' => $this->department_id,
            'category_id' => $this->category_id,
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority,
        ]);

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
