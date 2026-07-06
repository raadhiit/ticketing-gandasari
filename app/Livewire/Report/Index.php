<?php

namespace App\Livewire\Report;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Exports\TicketReportExport;
use App\Models\Department;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Carbon\Carbon;

#[Title('Laporan')]
class Index extends Component
{
    use WithPagination;

    public string $startDate = '';

    public string $endDate = '';

    public string $status = '';

    public string $departmentId = '';

    public string $priority = '';

    public ?int $exportTotal = null;

    public int $perPage = 10;

    public function mount(): void
    {
        $this->startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');

        if (! auth()->user()->can('report.view')) {
            $this->redirect(route('tickets.index'), navigate: true);
        }
    }

    public function updatedStartDate(): void
    {
        $this->resetPage();
    }

    public function updatedEndDate(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function updatedDepartmentId(): void
    {
        $this->resetPage();
    }

    public function updatedPriority(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['startDate', 'endDate', 'status', 'departmentId', 'priority']);
        $this->resetPage();
    }

    private function applyFilters(Builder $query): Builder
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
        $filtered = $this->applyFilters(Ticket::query());

        $statusCounts = (clone $filtered)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $tickets = (clone $filtered)
            ->with(['requester', 'department', 'category', 'activeAssignment.assignedTo'])
            ->latest()
            ->paginate($this->perPage);

        $stats = [
            'total' => $tickets->total(),
            'open' => $statusCounts['OPEN'] ?? 0,
            'in_progress' => $statusCounts['IN_PROGRESS'] ?? 0,
            'resolved' => $statusCounts['RESOLVED'] ?? 0,
            'closed' => $statusCounts['CLOSED'] ?? 0,
        ];

        return view('livewire.report.index', [
            'stats' => $stats,
            'tickets' => $tickets,
            'departments' => Department::orderBy('name')->pluck('name', 'id'),
            'statuses' => TicketStatus::cases(),
            'priorities' => TicketPriority::cases(),
        ]);
    }

    public function confirmExport(): void
    {
        if (empty($this->startDate) || empty($this->endDate)) {
            $this->dispatch(
                'toast-show',
                slots: [
                    'heading' => __('Perhatian'),
                    'text' => __('Pilih rentang tanggal terlebih dahulu'),
                ],
                dataset: ['variant' => 'warning']
            );

            return;
        }

        $filtered = $this->applyFilters(Ticket::query());

        $this->exportTotal = (clone $filtered)->count();

        if ($this->exportTotal === 0) {
            $this->dispatch(
                'toast-show',
                slots: [
                    'heading' => __('Data Kosong'),
                    'text' => __('Tidak ada data untuk diexport'),
                ],
                dataset: ['variant' => 'info']
            );

            return;
        }

        $this->dispatch(
            'confirm-open',
            name: 'confirm-export',
            title: __('Export Data'),
            message: __('Yakin ingin mengexport :count data ticket?', ['count' => $this->exportTotal]),
            method: 'exportXlsx',
            variant: 'primary',
            confirmLabel: __('Ya')
        );

        $this->dispatch('modal-show', name: 'confirm-export');
    }

    public function exportXlsx(): BinaryFileResponse
    {
        $filtered = $this->applyFilters(Ticket::query());

        $tickets = (clone $filtered)
            ->with(['requester', 'department', 'category', 'activeAssignment.assignedTo'])
            ->latest()
            ->get();

        return Excel::download(
            new TicketReportExport(
                $tickets,
                $this->startDate ?: null,
                $this->endDate ?: null,
            ),
            'laporan-ticket-' . now()->format('Ymd-His') . '.xlsx',
        );
    }
}
