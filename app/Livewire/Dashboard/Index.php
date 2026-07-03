<?php

namespace App\Livewire\Dashboard;

use App\Models\Department;
use App\Models\Ticket;
use App\Models\TicketCategory;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

#[Title('Dashboard')]
class Index extends Component
{
    public string $period = 'all';

    public function mount(): void
    {
        if (! Auth::user()->can('ticket.assign')) {
            $this->redirect(route('tickets.index'), navigate: true);
        }
    }

    private function scopePeriod(Builder $query): Builder
    {
        return $query
            ->when($this->period === 'today', fn($q) => $q->whereDate('created_at', today()))
            ->when($this->period === 'week', fn($q) => $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]))
            ->when($this->period === 'month', fn($q) => $q->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year));
    }

    public function render()
    {
        $base = Ticket::query();
        $periodic = $this->scopePeriod(clone $base);

        $stats = [
            'total' => (clone $periodic)->count(),
            'open' => (clone $periodic)->where('status', 'OPEN')->count(),
            'in_progress' => (clone $periodic)->where('status', 'IN_PROGRESS')->count(),
            'resolved' => (clone $periodic)->where('status', 'RESOLVED')->count(),
            'closed' => (clone $periodic)->where('status', 'CLOSED')->count(),
        ];

        $statsByPriority = [
            'URGENT' => (clone $periodic)->where('priority', 'URGENT')->count(),
            'HIGH' => (clone $periodic)->where('priority', 'HIGH')->count(),
            'MEDIUM' => (clone $periodic)->where('priority', 'MEDIUM')->count(),
            'LOW' => (clone $periodic)->where('priority', 'LOW')->count(),
        ];

        $perDepartemen = Department::withCount(['tickets' => function ($q) {
            $this->scopePeriod($q);
        }])->get();

        $perKategori = TicketCategory::withCount(['tickets' => function ($q) {
            $this->scopePeriod($q);
        }])->get();

        $myTickets = Ticket::whereHas('activeAssignment', fn($q) => $q->where('assigned_to', Auth::id()))
            ->whereIn('status', ['OPEN', 'IN_PROGRESS'])
            ->with(['requester', 'department', 'category'])
            ->latest()
            ->get();

        $needsAction = Ticket::whereIn('status', ['OPEN', 'IN_PROGRESS'])
            ->where('priority', 'URGENT')
            ->with(['requester', 'department', 'category', 'activeAssignment.assignedTo'])
            ->latest()
            ->get();

        $recentTickets = Ticket::with(['requester', 'department', 'category'])
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.dashboard.index', [
            'stats' => $stats,
            'statsByPriority' => $statsByPriority,
            'perDepartemen' => $perDepartemen,
            'perKategori' => $perKategori,
            'myTickets' => $myTickets,
            'needsAction' => $needsAction,
            'recentTickets' => $recentTickets,
        ]);
    }
}
