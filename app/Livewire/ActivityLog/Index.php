<?php

namespace App\Livewire\ActivityLog;

use App\Models\ActivityLog;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Activity Log')]
class Index extends Component
{
    use WithPagination;

    public string $startDate = '';

    public string $endDate = '';

    public string $actionFilter = '';

    public function render()
    {
        $query = ActivityLog::query()
            ->with('user')
            ->when($this->startDate, fn ($q) => $q->whereDate('created_at', '>=', $this->startDate))
            ->when($this->endDate, fn ($q) => $q->whereDate('created_at', '<=', $this->endDate))
            ->when($this->actionFilter, fn ($q) => $q->where('action', $this->actionFilter))
            ->latest('created_at');

        return view('livewire.activity-log.index', [
            'logs' => $query->paginate(20),
        ]);
    }
}
