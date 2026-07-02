<?php

namespace App\Livewire\Report;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Exports\TicketReportExport;
use App\Models\Department;
use App\Models\Ticket;
use Livewire\Attributes\Title;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

#[Title('Laporan')]
class Index extends Component
{
    public string $startDate = '';

    public string $endDate = '';

    public string $status = '';

    public string $departmentId = '';

    public string $priority = '';

    public ?int $exportTotal = null;

    public function mount(): void
    {
        if (! auth()->user()->can('report.view')) {
            $this->redirect(route('tickets.index'), navigate: true);
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['startDate', 'endDate', 'status', 'departmentId', 'priority']);
    }

    private function applyFilters($query)
    {
        return $query
            ->when($this->startDate, fn($q) => $q->whereDate('created_at', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('created_at', '<=', $this->endDate))
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->when($this->departmentId, fn($q) => $q->where('department_id', $this->departmentId))
            ->when($this->priority, fn($q) => $q->where('priority', $this->priority));
    }

    public function render()
    {
        $base = Ticket::query();
        $filtered = $this->applyFilters(clone $base);

        $stats = [
            'total' => (clone $filtered)->count(),
            'open' => (clone $filtered)->where('status', 'OPEN')->count(),
            'in_progress' => (clone $filtered)->where('status', 'IN_PROGRESS')->count(),
            'resolved' => (clone $filtered)->where('status', 'RESOLVED')->count(),
            'closed' => (clone $filtered)->where('status', 'CLOSED')->count(),
        ];

        $tickets = (clone $filtered)
            ->with(['requester', 'department', 'category', 'activeAssignment.assignedTo'])
            ->latest()
            ->get();

        $departments = Department::orderBy('name')->pluck('name', 'id');

        return view('livewire.report.index', [
            'stats' => $stats,
            'tickets' => $tickets,
            'departments' => $departments,
            'statuses' => TicketStatus::cases(),
            'priorities' => TicketPriority::cases(),
        ]);
    }

    public function confirmExport(): void
    {
        if (empty($this->startDate) || empty($this->endDate)) {
            $this->dispatch('toast-show', slots: ['heading' => __('Perhatian'), 'text' => __('Pilih rentang tanggal terlebih dahulu')], dataset: ['variant' => 'warning']);

            return;
        }

        $base = Ticket::query();
        $filtered = $this->applyFilters(clone $base);
        $this->exportTotal = (clone $filtered)->count();

        if ($this->exportTotal === 0) {
            $this->dispatch('toast-show', slots: ['heading' => __('Data Kosong'), 'text' => __('Tidak ada data untuk diexport')], dataset: ['variant' => 'info']);

            return;
        }

        $this->dispatch('confirm-open', name: 'confirm-export', title: __('Export Data'), message: __('Yakin ingin mengexport :count data ticket?', ['count' => $this->exportTotal]), method: 'exportXlsx', variant: 'primary', confirmLabel: __('Ya'));
        $this->dispatch('modal-show', name: 'confirm-export');
    }

    public function exportXlsx(): BinaryFileResponse
    {
        $base = Ticket::query();
        $filtered = $this->applyFilters(clone $base);

        $tickets = (clone $filtered)
            ->with(['requester', 'department', 'category', 'activeAssignment.assignedTo'])
            ->latest()
            ->get();

        return Excel::download(
            new TicketReportExport($tickets),
            'laporan-ticket-' . now()->format('Ymd-His') . '.xlsx',
        );
    }
}
