<?php

namespace App\Livewire\Ticket;

use App\Models\Ticket;
use App\Models\TicketCategory;
use Carbon\CarbonImmutable;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Tickets')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public string $priorityFilter = '';

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    public ?CarbonImmutable $lastCheck = null;

    public bool $hasNewTickets = false;

    protected $queryString = ['search', 'statusFilter', 'priorityFilter', 'sortField', 'sortDirection'];

    public function mount(): void
    {
        $this->lastCheck = now();
    }

    public function checkNewTickets(): void
    {
        $query = Ticket::query()->where('created_at', '>', $this->lastCheck);

        if (! auth()->user()->can('ticket.view')) {
            $query->where('requester_id', auth()->id());
        }

        $this->hasNewTickets = $query->exists();

        if ($this->hasNewTickets) {
            $this->lastCheck = now();
            $this->resetPage();
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingPriorityFilter(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $query = Ticket::query()
            ->with(['requester', 'department', 'category'])
            ->when(! auth()->user()->can('ticket.view'), fn ($q) => $q->where('requester_id', auth()->id()))
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('ticket_number', 'like', '%'.$this->search.'%')
                    ->orWhere('title', 'like', '%'.$this->search.'%');
            }))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->priorityFilter, fn ($q) => $q->where('priority', $this->priorityFilter))
            ->orderBy($this->sortField, $this->sortDirection);

        $tickets = $query->paginate(15);

        $counts = [
            'open' => Ticket::where('status', 'OPEN')->count(),
            'assigned' => Ticket::where('status', 'ASSIGNED')->count(),
            'in_progress' => Ticket::where('status', 'IN_PROGRESS')->count(),
            'closed' => Ticket::where('status', 'CLOSED')->count(),
        ];

        $categories = TicketCategory::pluck('name', 'id');

        return view('livewire.ticket.index', [
            'tickets' => $tickets,
            'counts' => $counts,
            'categories' => $categories,
        ]);
    }
}
