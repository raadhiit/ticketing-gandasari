@php
    $kpis = [
        ['label' => 'Total', 'count' => $stats['total'], 'caption' => 'Semua ticket', 'color' => '#343f50', 'status' => null],
        ['label' => 'Open', 'count' => $stats['open'], 'caption' => 'Ticket baru / belum diproses', 'color' => '#2563eb', 'status' => 'OPEN'],
        ['label' => 'In Progress', 'count' => $stats['in_progress'], 'caption' => 'Sedang dikerjakan', 'color' => '#d97706', 'status' => 'IN_PROGRESS'],
        ['label' => 'Resolved', 'count' => $stats['resolved'], 'caption' => 'Sudah diselesaikan', 'color' => '#059669', 'status' => 'RESOLVED'],
        ['label' => 'Closed', 'count' => $stats['closed'], 'caption' => 'Sudah ditutup', 'color' => '#e11d48', 'status' => 'CLOSED'],
    ];

    $priorityBars = [
        'URGENT' => ['label' => 'Urgent', 'color' => 'var(--color-pri-urgent)'],
        'HIGH' => ['label' => 'High', 'color' => 'var(--color-pri-high)'],
        'MEDIUM' => ['label' => 'Medium', 'color' => 'var(--color-pri-medium)'],
        'LOW' => ['label' => 'Low', 'color' => 'var(--color-pri-low)'],
    ];

    $maxPriority = max(1, ...array_values($statsByPriority));
    $maxCategory = max(1, (int) $perKategori->max('tickets_count'));
    $maxDepartment = max(1, (int) $perDepartemen->max('tickets_count'));
@endphp

<div class="flex flex-col gap-3">
    <div class="tbar min-h-[68px] !gap-x-4 !gap-y-2 !py-2" role="toolbar" aria-label="{{ __('Filter periode') }}">
        <label for="per" class="text-[11px] font-bold tracking-[.03em] text-ink-label uppercase">{{ __('Periode') }}</label>
        <select id="per" wire:model.live="period" class="sel min-w-[180px]">
            <option value="all">{{ __('Semua Periode') }}</option>
            <option value="today">{{ __('Hari ini') }}</option>
            <option value="week">{{ __('Minggu ini') }}</option>
            <option value="month">{{ __('Bulan ini') }}</option>
            <option value="30d">{{ __('30 hari terakhir') }}</option>
            <option value="year">{{ __('Tahun ini') }}</option>
        </select>

        <span class="ml-auto text-[13px] text-muted">{{ __('Data per') }} {{ now()->translatedFormat('d M Y') }}</span>
    </div>

    <x-panel :title="__('Ringkasan Ticket')">
        <div class="kpi">
            @foreach ($kpis as $kpi)
                <a href="{{ route('tickets.index', $kpi['status'] ? ['statusFilter' => $kpi['status']] : []) }}" wire:navigate style="--c: {{ $kpi['color'] }}">
                    <div class="kpi-l">{{ __($kpi['label']) }}</div>
                    <div class="kpi-n">{{ $kpi['count'] }}</div>
                    <div class="kpi-c">{{ __($kpi['caption']) }}</div>
                </a>
            @endforeach
        </div>
    </x-panel>

    <div class="grid grid-cols-1 items-start gap-3 min-[701px]:grid-cols-2 min-[1101px]:grid-cols-3">
        <x-panel :title="__('Ticket per Prioritas')">
            <div class="bars">
                @foreach ($priorityBars as $key => $meta)
                    @php $count = $statsByPriority[$key]; $first = $loop->first; @endphp

                    <div @class(['nm', 'is-first' => $first])><span class="dot" style="background: {{ $meta['color'] }}"></span>{{ __($meta['label']) }}</div>
                    <div @class(['bar', 'is-first' => $first])>
                        @if ($count > 0)<i style="--c: {{ $meta['color'] }}; width: {{ round($count / $maxPriority * 100) }}%"></i>@endif
                    </div>
                    <div @class(['ct', 'is-zero' => $count === 0, 'is-first' => $first])>
                        @if ($count > 0)
                            <a href="{{ route('tickets.index', ['priorityFilter' => $key]) }}" wire:navigate>{{ $count }}</a>
                        @else
                            {{ $count }}
                        @endif
                    </div>
                @endforeach
            </div>
        </x-panel>

        <x-panel :title="__('Ticket per Kategori')">
            <div class="bars">
                @foreach ($perKategori as $category)
                    @php $count = $category->tickets_count; $first = $loop->first; @endphp

                    <div @class(['nm', 'is-first' => $first]) title="{{ $category->name }}">{{ $category->name }}</div>
                    <div @class(['bar', 'is-first' => $first])>
                        @if ($count > 0)<i style="width: {{ round($count / $maxCategory * 100) }}%"></i>@endif
                    </div>
                    <div @class(['ct', 'is-zero' => $count === 0, 'is-first' => $first])>
                        @if ($count > 0)
                            <a href="{{ route('tickets.index', ['categoryFilter' => $category->id]) }}" wire:navigate>{{ $count }}</a>
                        @else
                            {{ $count }}
                        @endif
                    </div>
                @endforeach
            </div>
        </x-panel>

        <x-panel :title="__('Ticket per Departemen')">
            <div class="bars">
                @foreach ($perDepartemen as $department)
                    @php $count = $department->tickets_count; $first = $loop->first; @endphp

                    <div @class(['nm', 'is-first' => $first]) title="{{ $department->name }}">{{ $department->name }}</div>
                    <div @class(['bar', 'is-first' => $first])>
                        @if ($count > 0)<i style="width: {{ round($count / $maxDepartment * 100) }}%"></i>@endif
                    </div>
                    <div @class(['ct', 'is-zero' => $count === 0, 'is-first' => $first])>
                        @if ($count > 0)
                            <a href="{{ route('tickets.index', ['departmentFilter' => $department->id]) }}" wire:navigate>{{ $count }}</a>
                        @else
                            {{ $count }}
                        @endif
                    </div>
                @endforeach
            </div>
        </x-panel>
    </div>

    <div class="grid grid-cols-1 items-start gap-3 min-[1101px]:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">
        <x-panel :title="__('Ticket Terbaru')">
            <div class="overflow-x-auto">
                <table class="tbl min-w-[560px]">
                    <thead>
                        <tr>
                            <th class="!border-t-0">{{ __('No. Ticket') }}</th>
                            <th class="!border-t-0">{{ __('Judul') }}</th>
                            <th class="!border-t-0">{{ __('Pelapor') }}</th>
                            <th class="!border-t-0">{{ __('Prioritas') }}</th>
                            <th class="!border-t-0">{{ __('Status') }}</th>
                            <th class="!border-t-0">{{ __('Dibuat') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentTickets as $ticket)
                            <tr wire:key="recent-{{ $ticket->id }}">
                                <td class="whitespace-nowrap"><a href="{{ route('tickets.show', $ticket) }}" wire:navigate>{{ $ticket->ticket_number }}</a></td>
                                <td class="font-bold text-ink-strong">{{ $ticket->title }}</td>
                                <td>{{ $ticket->requester_name ?: $ticket->requester?->name }}</td>
                                <td class="whitespace-nowrap"><x-priority-dot :priority="$ticket->priority" /></td>
                                <td><x-status-pill :status="$ticket->status" /></td>
                                <td class="whitespace-nowrap text-muted">{{ $ticket->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-muted">{{ __('Belum ada ticket.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-line px-3 py-2.5 text-sm font-semibold">
                <a href="{{ route('tickets.index') }}" wire:navigate class="no-underline">{{ __('Lihat semua ticket') }}</a>
            </div>
        </x-panel>

        <x-panel :title="__('Ticket Saya (Aktif)')">
            @forelse ($myTickets as $ticket)
                <div @class(['px-3 py-2.5', 'border-t border-line' => ! $loop->first]) wire:key="mine-{{ $ticket->id }}">
                    <div class="flex items-center justify-between gap-2">
                        <a href="{{ route('tickets.show', $ticket) }}" wire:navigate class="text-sm font-medium no-underline">{{ $ticket->ticket_number }}</a>
                        <x-status-pill :status="$ticket->status" />
                    </div>
                    <div class="mt-0.5 truncate text-sm font-bold text-ink-strong">{{ $ticket->title }}</div>
                    <div class="mt-0.5 text-[13px] text-muted">{{ $ticket->requester_name ?: $ticket->requester?->name }} · {{ $ticket->created_at->format('d M Y') }}</div>
                </div>
            @empty
                <div class="empty-state">
                    <b>{{ __('Tidak ada ticket aktif') }}</b>
                    {{ __('Semua ticket Anda telah diselesaikan.') }}
                </div>
            @endforelse
        </x-panel>
    </div>
</div>
