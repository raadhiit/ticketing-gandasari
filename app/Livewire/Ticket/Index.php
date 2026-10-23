<?php

namespace App\Livewire\Ticket;

use App\Models\Department;
use App\Models\Ticket;
use App\Models\TicketCategory;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
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

    public string $departmentFilter = '';

    public string $categoryFilter = '';

    /**
     * Support only: limit the list to tickets currently assigned to the signed-in user.
     */
    public bool $mine = false;

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    public ?CarbonImmutable $lastCheck = null;

    public bool $hasNewTickets = false;

    protected $queryString = ['search', 'statusFilter', 'priorityFilter', 'departmentFilter', 'categoryFilter', 'mine', 'sortField', 'sortDirection'];

    public function mount(): void
    {
        $this->lastCheck = now();
    }

    public function checkNewTickets(): void
    {
        $query = Ticket::query()->where('created_at', '>', $this->lastCheck);

        if (! Auth::user()?->can('ticket.assign')) {
            $query->where('requester_id', Auth::id());
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

    public function updatingDepartmentFilter(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter(): void
    {
        $this->resetPage();
    }

    public function updatingMine(): void
    {
        $this->resetPage();
    }

    public function refresh(): void
    {
        $this->lastCheck = now();
        $this->hasNewTickets = false;
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
            ->with(['requester', 'department', 'category', 'activeAssignment.assignedTo'])
            ->when(! Auth::user()?->can('ticket.assign'), fn ($q) => $q->where('requester_id', Auth::id()))
            ->when($this->mine && Auth::user()?->can('ticket.assign'), fn ($q) => $q->whereHas('activeAssignment', fn ($a) => $a->where('assigned_to', Auth::id())))
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('ticket_number', 'like', '%'.$this->search.'%')
                    ->orWhere('title', 'like', '%'.$this->search.'%');
            }))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->priorityFilter, fn ($q) => $q->where('priority', $this->priorityFilter))
            ->when($this->departmentFilter, fn ($q) => $q->where('department_id', $this->departmentFilter))
            ->when($this->categoryFilter, fn ($q) => $q->where('category_id', $this->categoryFilter))
            ->orderBy($this->sortField, $this->sortDirection);

        $tickets = $query->paginate(15);

        $user = Auth::user();

        $countQuery = Ticket::query()
            ->when(! $user?->can('ticket.assign'), fn ($q) => $q->where('requester_id', $user?->id))
            ->when($this->mine && $user?->can('ticket.assign'), fn ($q) => $q->whereHas('activeAssignment', fn ($a) => $a->where('assigned_to', $user->id)));

        $counts = [
            'open' => (clone $countQuery)->where('status', 'OPEN')->count(),
            'in_progress' => (clone $countQuery)->where('status', 'IN_PROGRESS')->count(),
            'resolved' => (clone $countQuery)->where('status', 'RESOLVED')->count(),
            'closed' => (clone $countQuery)->where('status', 'CLOSED')->count(),
        ];

        return view('livewire.ticket.index', [
            'tickets' => $tickets,
            'counts' => $counts,
            'activeDepartment' => $this->departmentFilter ? Department::whereKey($this->departmentFilter)->value('name') : null,
            'activeCategory' => $this->categoryFilter ? TicketCategory::whereKey($this->categoryFilter)->value('name') : null,
        ]);
    }
}
