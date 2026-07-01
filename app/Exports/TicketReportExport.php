<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TicketReportExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    public function __construct(
        private Collection $tickets,
    ) {}

    public function collection(): Collection
    {
        return $this->tickets;
    }

    public function headings(): array
    {
        return [
            'Ticket Number',
            'Judul',
            'Requester',
            'Departemen',
            'Kategori',
            'Prioritas',
            'Status',
            'Assigned To',
            'Dibuat',
            'Diupdate',
        ];
    }

    public function map($ticket): array
    {
        return [
            $ticket->ticket_number,
            $ticket->title,
            $ticket->requester_name ?: $ticket->requester?->name,
            $ticket->department?->name,
            $ticket->category?->name,
            $ticket->priority,
            str_replace('_', ' ', $ticket->status),
            $ticket->activeAssignment?->assignedTo?->name,
            $ticket->created_at->format('Y-m-d H:i'),
            $ticket->updated_at->format('Y-m-d H:i'),
        ];
    }
}
