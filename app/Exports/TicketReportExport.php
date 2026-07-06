<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class TicketReportExport implements FromCollection, ShouldAutoSize, WithMapping, WithEvents
{
    public function __construct(
        private Collection $tickets,
        private ?string $startDate = null,
        private ?string $endDate = null,
    ) {}

    public function collection(): Collection
    {
        return $this->tickets;
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

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $period = ($this->startDate && $this->endDate)
                    ? "{$this->startDate} sampai {$this->endDate}"
                    : 'Semua periode';

                $sheet->insertNewRowBefore(1, 5);

                $sheet->setCellValue('A1', 'LAPORAN DATA TICKET');
                $sheet->setCellValue('A2', 'Periode: ' . $period);
                $sheet->setCellValue('A3', 'Tanggal Export: ' . now()->format('Y-m-d H:i'));

                $headings = [
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

                $sheet->fromArray($headings, null, 'A5');

                $sheet->mergeCells('A1:J1');
                $sheet->mergeCells('A2:J2');
                $sheet->mergeCells('A3:J3');

                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A2:A3')->getFont()->setSize(11);
                $sheet->getStyle('A5:J5')->getFont()->setBold(true);

                $sheet->getStyle('A5:J5')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFE5E7EB');

                $sheet->getStyle('A5:J' . $sheet->getHighestRow())
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $sheet->freezePane('A6');
            },
        ];
    }
}