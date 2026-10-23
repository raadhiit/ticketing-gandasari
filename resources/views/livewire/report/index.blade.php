@php
    $kpis = [
        ['label' => 'Total', 'count' => $stats['total'], 'caption' => 'Semua ticket sesuai filter', 'color' => '#343f50', 'status' => ''],
        ['label' => 'Open', 'count' => $stats['open'], 'caption' => 'Ticket baru', 'color' => '#2563eb', 'status' => 'OPEN'],
        ['label' => 'In Progress', 'count' => $stats['in_progress'], 'caption' => 'Sedang dikerjakan', 'color' => '#d97706', 'status' => 'IN_PROGRESS'],
        ['label' => 'Resolved', 'count' => $stats['resolved'], 'caption' => 'Sudah diselesaikan', 'color' => '#059669', 'status' => 'RESOLVED'],
        ['label' => 'Closed', 'count' => $stats['closed'], 'caption' => 'Sudah ditutup', 'color' => '#e11d48', 'status' => 'CLOSED'],
    ];
@endphp

<div class="flex flex-col gap-3">
    <div class="tbar" role="toolbar" aria-label="{{ __('Aksi') }}">
        <button type="button" class="tbar-btn" wire:click="confirmExport" wire:loading.attr="disabled" wire:target="confirmExport,exportXlsx">{{ __('Export XLSX') }}</button>
        <button type="button" class="tbar-btn" wire:click="resetFilters">{{ __('Reset Filter') }}</button>
    </div>

    <x-panel :title="__('Filter Laporan')">
        <div class="kv kv-tall">
            <div class="kv-k kv-first"><label for="d1">{{ __('Dari Tanggal') }}</label></div>
            <div class="kv-v kv-first"><input id="d1" type="date" wire:model.live="startDate" class="inp"></div>
            <div class="kv-k kv-first"><label for="d2">{{ __('Sampai Tanggal') }}</label></div>
            <div class="kv-v kv-first"><input id="d2" type="date" wire:model.live="endDate" class="inp"></div>
            <div class="kv-k kv-first"><label for="st">{{ __('Status') }}</label></div>
            <div class="kv-v kv-first">
                <select id="st" wire:model.live="status" class="sel">
                    <option value="">{{ __('Semua Status') }}</option>
                    @foreach ($statuses as $case)
                        <option value="{{ $case->value }}">{{ ucwords(strtolower(str_replace('_', ' ', $case->value))) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="kv-k"><label for="dp">{{ __('Departemen') }}</label></div>
            <div class="kv-v">
                <select id="dp" wire:model.live="departmentId" class="sel">
                    <option value="">{{ __('Semua Departemen') }}</option>
                    @foreach ($departments as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="kv-k"><label for="pr">{{ __('Prioritas') }}</label></div>
            <div class="kv-v">
                <select id="pr" wire:model.live="priority" class="sel">
                    <option value="">{{ __('Semua Prioritas') }}</option>
                    @foreach ($priorities as $case)
                        <option value="{{ $case->value }}">{{ ucfirst(strtolower($case->value)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="kv-k kv-blank !bg-white"></div>
            <div class="kv-v kv-blank text-[13px] text-muted">{{ __('Filter langsung diterapkan. Export hanya memuat data sesuai filter.') }}</div>
        </div>
    </x-panel>

    <x-panel :title="__('Ringkasan (sesuai filter)')">
        <div class="kpi">
            @foreach ($kpis as $kpi)
                <a href="#" wire:click.prevent="$set('status', '{{ $kpi['status'] }}')" style="--c: {{ $kpi['color'] }}">
                    <div class="kpi-l">{{ __($kpi['label']) }}</div>
                    <div class="kpi-n">{{ $kpi['count'] }}</div>
                    <div class="kpi-c">{{ __($kpi['caption']) }}</div>
                </a>
            @endforeach
        </div>
    </x-panel>

    <x-panel :title="__('Data Ticket')" :count="$tickets->total()">
        <div class="px-4 py-2 text-[13px] text-ink-label">{{ __('Klik nomor ticket untuk melihat detail.') }}</div>

        <div class="overflow-x-auto">
            <table class="tbl min-w-[1180px]">
                <thead>
                    <tr>
                        <th>{{ __('No. Ticket') }}</th>
                        <th>{{ __('Judul') }}</th>
                        <th>{{ __('Pelapor') }}</th>
                        <th>{{ __('Departemen') }}</th>
                        <th>{{ __('Kategori') }}</th>
                        <th>{{ __('Prioritas') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Ditangani oleh') }}</th>
                        <th>{{ __('Dibuat') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($tickets as $ticket)
                        @php $agentName = $ticket->activeAssignment?->assignedTo?->name; @endphp

                        <tr wire:key="report-{{ $ticket->id }}">
                            <td class="whitespace-nowrap"><a href="{{ route('tickets.show', $ticket) }}" wire:navigate>{{ $ticket->ticket_number }}</a></td>
                            <td class="font-bold text-ink-strong">{{ $ticket->title }}</td>
                            <td>{{ $ticket->requester_name ?: $ticket->requester?->name }}</td>
                            <td>{{ $ticket->department?->name }}</td>
                            <td>{{ $ticket->category?->name }}</td>
                            <td class="whitespace-nowrap"><x-priority-dot :priority="$ticket->priority" /></td>
                            <td><x-status-pill :status="$ticket->status" /></td>
                            <td class="whitespace-nowrap">
                                @if ($agentName)
                                    {{ $agentName }}
                                @else
                                    <span class="text-muted">{{ __('Belum ditugaskan') }}</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap text-muted">{{ $ticket->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-muted">{{ __('Tidak ada ticket yang sesuai filter.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $tickets->links() }}
    </x-panel>

    <x-confirm-dialog name="confirm-export" />
</div>
