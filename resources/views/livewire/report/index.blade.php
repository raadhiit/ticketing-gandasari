@php
    $priorityMeta = [
        'URGENT' => [
            'label' => 'Urgent',
            'badge' => 'bg-rose-50 text-rose-700 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-300 dark:ring-rose-500/20',
            'dot' => 'bg-rose-500',
        ],
        'HIGH' => [
            'label' => 'High',
            'badge' => 'bg-orange-50 text-orange-700 ring-orange-200 dark:bg-orange-500/10 dark:text-orange-300 dark:ring-orange-500/20',
            'dot' => 'bg-orange-500',
        ],
        'MEDIUM' => [
            'label' => 'Medium',
            'badge' => 'bg-blue-50 text-blue-700 ring-blue-200 dark:bg-blue-500/10 dark:text-blue-300 dark:ring-blue-500/20',
            'dot' => 'bg-blue-500',
        ],
        'LOW' => [
            'label' => 'Low',
            'badge' => 'bg-slate-50 text-slate-700 ring-slate-200 dark:bg-slate-500/10 dark:text-slate-300 dark:ring-slate-500/20',
            'dot' => 'bg-slate-500',
        ],
    ];

    $statusMeta = [
        'OPEN' => [
            'label' => 'Open',
            'badge' => 'bg-sky-50 text-sky-700 ring-sky-200 dark:bg-sky-500/10 dark:text-sky-300 dark:ring-sky-500/20',
        ],
        'IN_PROGRESS' => [
            'label' => 'In Progress',
            'badge' => 'bg-amber-50 text-amber-700 ring-amber-200 dark:bg-amber-500/10 dark:text-amber-300 dark:ring-amber-500/20',
        ],
        'RESOLVED' => [
            'label' => 'Resolved',
            'badge' => 'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-500/20',
        ],
        'CLOSED' => [
            'label' => 'Closed',
            'badge' => 'bg-rose-50 text-rose-700 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-300 dark:ring-rose-500/20',
        ],
    ];

    $statCards = [
        [
            'label' => 'Total',
            'description' => 'Semua ticket sesuai filter',
            'value' => $stats['total'],
            'border' => 'border-zinc-200 dark:border-zinc-700',
            'text' => 'text-zinc-700 dark:text-zinc-300',
            'iconBg' => 'bg-zinc-700',
            'shadow' => 'shadow-zinc-700/20',
        ],
        [
            'label' => 'Open',
            'description' => 'Ticket baru',
            'value' => $stats['open'],
            'border' => 'border-sky-200 dark:border-sky-500/20',
            'text' => 'text-sky-700 dark:text-sky-300',
            'iconBg' => 'bg-sky-500',
            'shadow' => 'shadow-sky-500/25',
        ],
        [
            'label' => 'In Progress',
            'description' => 'Sedang dikerjakan',
            'value' => $stats['in_progress'],
            'border' => 'border-amber-200 dark:border-amber-500/20',
            'text' => 'text-amber-700 dark:text-amber-300',
            'iconBg' => 'bg-amber-500',
            'shadow' => 'shadow-amber-500/25',
        ],
        [
            'label' => 'Resolved',
            'description' => 'Sudah diselesaikan',
            'value' => $stats['resolved'],
            'border' => 'border-emerald-200 dark:border-emerald-500/20',
            'text' => 'text-emerald-700 dark:text-emerald-300',
            'iconBg' => 'bg-emerald-500',
            'shadow' => 'shadow-emerald-500/25',
        ],
        [
            'label' => 'Closed',
            'description' => 'Sudah ditutup',
            'value' => $stats['closed'],
            'border' => 'border-rose-200 dark:border-rose-500/20',
            'text' => 'text-rose-700 dark:text-rose-300',
            'iconBg' => 'bg-rose-500',
            'shadow' => 'shadow-rose-500/25',
        ],
    ];
@endphp

<div class="space-y-6">
    <x-confirm-dialog name="confirm-export" />

    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white md:text-3xl">
                {{ __('Laporan') }}
            </h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400 md:text-base">
                {{ __('Rekap, filter, dan export data ticket berdasarkan kebutuhan laporan') }}
            </p>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <flux:button
                wire:click="resetFilters"
                variant="ghost"
                icon="x-mark"
                class="h-11 rounded-2xl px-5"
            >
                {{ __('Reset') }}
            </flux:button>

            <flux:button
                wire:click="confirmExport"
                wire:loading.attr="disabled"
                icon="arrow-down-tray"
                variant="primary"
                class="h-11 rounded-2xl px-5 shadow-sm"
            >
                {{ __('Export XLSX') }}
            </flux:button>
        </div>
    </div>

    {{-- Filter Panel --}}
    <div class="overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div class="flex items-start gap-4 border-b border-zinc-100 px-5 py-5 dark:border-zinc-800">
            <div class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-violet-600 text-white shadow-lg shadow-violet-600/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013l-2.25 1.125A.75.75 0 0 1 9.75 19.826v-5.394a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                </svg>
            </div>

            <div>
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">
                    {{ __('Filter Laporan') }}
                </h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    {{ __('Gunakan filter agar data laporan lebih spesifik dan export tidak terlalu besar') }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2 xl:grid-cols-5">
            <flux:field>
                <flux:label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                    {{ __('Dari Tanggal') }}
                </flux:label>
                <flux:input
                    wire:model.live="startDate"
                    type="date"
                    class="h-12 rounded-2xl dark:bg-zinc-950 dark:border-zinc-700/50 dark:text-white"
                />
            </flux:field>

            <flux:field>
                <flux:label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                    {{ __('Sampai Tanggal') }}
                </flux:label>
                <flux:input
                    wire:model.live="endDate"
                    type="date"
                    class="h-12 rounded-2xl dark:bg-zinc-950 dark:border-zinc-700/50 dark:text-white"
                />
            </flux:field>

            <flux:field>
                <flux:label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                    {{ __('Status') }}
                </flux:label>
                <flux:select
                    wire:model.live="status"
                    class="h-12 rounded-2xl dark:bg-zinc-950 dark:border-zinc-700/50 dark:text-white"
                >
                    <option value="">{{ __('Semua Status') }}</option>
                    @foreach ($statuses as $s)
                        <option value="{{ $s->value }}">{{ str_replace('_', ' ', $s->value) }}</option>
                    @endforeach
                </flux:select>
            </flux:field>

            <flux:field>
                <flux:label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                    {{ __('Departemen') }}
                </flux:label>
                <flux:select
                    wire:model.live="departmentId"
                    class="h-12 rounded-2xl dark:bg-zinc-950 dark:border-zinc-700/50 dark:text-white"
                >
                    <option value="">{{ __('Semua Departemen') }}</option>
                    @foreach ($departments as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </flux:select>
            </flux:field>

            <flux:field>
                <flux:label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                    {{ __('Prioritas') }}
                </flux:label>
                <flux:select
                    wire:model.live="priority"
                    class="h-12 rounded-2xl dark:bg-zinc-950 dark:border-zinc-700/50 dark:text-white"
                >
                    <option value="">{{ __('Semua Prioritas') }}</option>
                    @foreach ($priorities as $p)
                        <option value="{{ $p->value }}">{{ $p->value }}</option>
                    @endforeach
                </flux:select>
            </flux:field>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
        @foreach ($statCards as $card)
            <div class="rounded-3xl border {{ $card['border'] }} bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:bg-zinc-900">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex size-14 items-center justify-center rounded-2xl {{ $card['iconBg'] }} text-white shadow-lg {{ $card['shadow'] }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h3m5 4H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5.586A2 2 0 0 1 14 4.586L18.414 9A2 2 0 0 1 19 10.414V18a2 2 0 0 1-2 2Z" />
                        </svg>
                    </div>

                    <div class="text-right">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] {{ $card['text'] }}">
                            {{ __($card['label']) }}
                        </p>
                        <p class="mt-1 text-4xl font-bold leading-none text-zinc-900 dark:text-white">
                            {{ $card['value'] }}
                        </p>
                    </div>
                </div>

                <p class="mt-6 text-sm text-zinc-500 dark:text-zinc-400">
                    {{ __($card['description']) }}
                </p>
            </div>
        @endforeach
    </div>

    {{-- Report Table --}}
    <div class="overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div class="flex flex-col gap-3 border-b border-zinc-100 px-5 py-5 dark:border-zinc-800 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                    {{ __('Data Ticket') }}
                </h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    {{ __('Klik baris ticket untuk melihat detail ticket') }}
                </p>
            </div>

            <span class="inline-flex w-fit items-center rounded-2xl bg-zinc-50 px-3 py-1.5 text-xs font-semibold text-zinc-600 ring-1 ring-inset ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-700">
                {{ $tickets->total() }} {{ __('ticket') }}
            </span>
        </div>

        {{-- Desktop Table --}}
        <div class="hidden overflow-x-auto xl:block">
            <table class="min-w-full">
                <thead class="bg-zinc-50/80 dark:bg-zinc-950/60">
                    <tr class="border-b border-zinc-100 dark:border-zinc-800">
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Ticket') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Judul') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Requester') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Departemen') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Kategori') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Prioritas') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Status') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Assigned') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Dibuat') }}</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse ($tickets as $ticket)
                        @php
                            $priority = $priorityMeta[$ticket->priority] ?? [
                                'label' => \Illuminate\Support\Str::headline($ticket->priority),
                                'badge' => 'bg-zinc-50 text-zinc-700 ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-700',
                                'dot' => 'bg-zinc-500',
                            ];

                            $status = $statusMeta[$ticket->status] ?? [
                                'label' => \Illuminate\Support\Str::headline($ticket->status),
                                'badge' => 'bg-zinc-50 text-zinc-700 ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-700',
                            ];
                        @endphp

                        <tr
                            onclick="window.location='{{ route('tickets.show', $ticket) }}'"
                            class="cursor-pointer transition hover:bg-zinc-50 dark:hover:bg-zinc-800/40"
                        >
                            <td class="px-5 py-4 align-top">
                                <span class="inline-flex rounded-xl bg-zinc-100 px-3 py-1 font-mono text-xs font-semibold text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                                    {{ $ticket->ticket_number }}
                                </span>
                            </td>

                            <td class="px-5 py-4 align-top">
                                <p class="max-w-[240px] truncate text-sm font-semibold text-zinc-900 dark:text-white">
                                    {{ $ticket->title }}
                                </p>
                            </td>

                            <td class="px-5 py-4 align-top text-sm text-zinc-600 dark:text-zinc-400">
                                {{ $ticket->requester_name ?: $ticket->requester?->name }}
                            </td>

                            <td class="px-5 py-4 align-top text-sm text-zinc-600 dark:text-zinc-400">
                                {{ $ticket->department?->name ?? '-' }}
                            </td>

                            <td class="px-5 py-4 align-top text-sm text-zinc-600 dark:text-zinc-400">
                                {{ $ticket->category?->name ?? '-' }}
                            </td>

                            <td class="px-5 py-4 align-top">
                                <span class="inline-flex items-center gap-2 rounded-xl px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $priority['badge'] }}">
                                    <span class="size-1.5 rounded-full {{ $priority['dot'] }}"></span>
                                    {{ strtoupper($priority['label']) }}
                                </span>
                            </td>

                            <td class="px-5 py-4 align-top">
                                <span class="inline-flex items-center gap-2 rounded-xl px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $status['badge'] }}">
                                    <span class="size-1.5 rounded-full bg-current opacity-80"></span>
                                    {{ strtoupper($status['label']) }}
                                </span>
                            </td>

                            <td class="px-5 py-4 align-top text-sm text-zinc-600 dark:text-zinc-400">
                                {{ $ticket->activeAssignment?->assignedTo?->name ?? '-' }}
                            </td>

                            <td class="px-5 py-4 align-top text-xs text-zinc-500 dark:text-zinc-400">
                                {{ $ticket->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-16">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <div class="relative mb-5">
                                        <div class="absolute inset-0 rounded-full bg-violet-400/20 blur-2xl dark:bg-violet-400/10"></div>
                                        <div class="relative flex size-24 items-center justify-center rounded-[2rem] bg-gradient-to-br from-violet-100 to-blue-100 text-violet-600 dark:from-violet-500/10 dark:to-blue-500/10 dark:text-violet-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h3m5 4H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5.586A2 2 0 0 1 14 4.586L18.414 9A2 2 0 0 1 19 10.414V18a2 2 0 0 1-2 2Z" />
                                            </svg>
                                        </div>
                                    </div>

                                    <h3 class="text-xl font-semibold text-zinc-900 dark:text-white">
                                        {{ __('Tidak ada data') }}
                                    </h3>
                                    <p class="mt-2 max-w-md text-sm leading-6 text-zinc-500 dark:text-zinc-400">
                                        {{ __('Tidak ada ticket yang cocok dengan filter laporan saat ini.') }}
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile / Tablet Cards --}}
        <div class="divide-y divide-zinc-100 dark:divide-zinc-800 xl:hidden">
            @forelse ($tickets as $ticket)
                @php
                    $priority = $priorityMeta[$ticket->priority] ?? [
                        'label' => \Illuminate\Support\Str::headline($ticket->priority),
                        'badge' => 'bg-zinc-50 text-zinc-700 ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-700',
                        'dot' => 'bg-zinc-500',
                    ];

                    $status = $statusMeta[$ticket->status] ?? [
                        'label' => \Illuminate\Support\Str::headline($ticket->status),
                        'badge' => 'bg-zinc-50 text-zinc-700 ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-700',
                    ];
                @endphp

                <a href="{{ route('tickets.show', $ticket) }}" wire:navigate class="block p-5 transition hover:bg-zinc-50 dark:hover:bg-zinc-800/40">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <span class="inline-flex rounded-xl bg-zinc-100 px-3 py-1 font-mono text-xs font-semibold text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                                {{ $ticket->ticket_number }}
                            </span>

                            <h3 class="mt-2 line-clamp-2 text-sm font-semibold text-zinc-900 dark:text-white">
                                {{ $ticket->title }}
                            </h3>

                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $ticket->requester_name ?: $ticket->requester?->name }}
                            </p>
                        </div>

                        <span class="shrink-0 text-xs text-zinc-500 dark:text-zinc-400">
                            {{ $ticket->created_at->format('d/m/Y') }}
                        </span>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <span class="inline-flex items-center gap-2 rounded-xl px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $priority['badge'] }}">
                            <span class="size-1.5 rounded-full {{ $priority['dot'] }}"></span>
                            {{ strtoupper($priority['label']) }}
                        </span>

                        <span class="inline-flex items-center gap-2 rounded-xl px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $status['badge'] }}">
                            <span class="size-1.5 rounded-full bg-current opacity-80"></span>
                            {{ strtoupper($status['label']) }}
                        </span>

                        <span class="inline-flex items-center rounded-xl bg-zinc-50 px-2.5 py-1 text-xs font-medium text-zinc-600 ring-1 ring-inset ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-700">
                            {{ $ticket->department?->name ?? __('Tanpa Departemen') }}
                        </span>
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-2 text-sm text-zinc-500 dark:text-zinc-400 sm:grid-cols-2">
                        <p>
                            <span class="font-medium text-zinc-700 dark:text-zinc-300">{{ __('Kategori') }}:</span>
                            {{ $ticket->category?->name ?? '-' }}
                        </p>

                        <p>
                            <span class="font-medium text-zinc-700 dark:text-zinc-300">{{ __('Assigned') }}:</span>
                            {{ $ticket->activeAssignment?->assignedTo?->name ?? '-' }}
                        </p>
                    </div>
                </a>
            @empty
                <div class="flex min-h-[320px] flex-col items-center justify-center px-6 py-12 text-center">
                    <div class="relative mb-5">
                        <div class="absolute inset-0 rounded-full bg-violet-400/20 blur-2xl dark:bg-violet-400/10"></div>
                        <div class="relative flex size-24 items-center justify-center rounded-[2rem] bg-gradient-to-br from-violet-100 to-blue-100 text-violet-600 dark:from-violet-500/10 dark:to-blue-500/10 dark:text-violet-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h3m5 4H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5.586A2 2 0 0 1 14 4.586L18.414 9A2 2 0 0 1 19 10.414V18a2 2 0 0 1-2 2Z" />
                            </svg>
                        </div>
                    </div>

                    <h3 class="text-xl font-semibold text-zinc-900 dark:text-white">
                        {{ __('Tidak ada data') }}
                    </h3>
                    <p class="mt-2 max-w-md text-sm leading-6 text-zinc-500 dark:text-zinc-400">
                        {{ __('Tidak ada ticket yang cocok dengan filter laporan saat ini.') }}
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($tickets->hasPages())
            <div class="border-t border-zinc-100 px-5 py-4 dark:border-zinc-800">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</div>