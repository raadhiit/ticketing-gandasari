@php
    $statusCards = [
        'open' => [
            'label' => 'Open',
            'description' => 'Ticket baru / belum diproses',
            'count' => $counts['open'],
            'border' => 'border-sky-200 dark:border-sky-500/20',
            'text' => 'text-sky-700 dark:text-sky-300',
            'iconBg' => 'bg-sky-500',
            'shadow' => 'shadow-sky-500/25',
        ],
        'in_progress' => [
            'label' => 'In Progress',
            'description' => 'Ticket sedang dikerjakan',
            'count' => $counts['in_progress'],
            'border' => 'border-amber-200 dark:border-amber-500/20',
            'text' => 'text-amber-700 dark:text-amber-300',
            'iconBg' => 'bg-amber-500',
            'shadow' => 'shadow-amber-500/25',
        ],
        'resolved' => [
            'label' => 'Resolved',
            'description' => 'Ticket sudah diselesaikan',
            'count' => $counts['resolved'],
            'border' => 'border-emerald-200 dark:border-emerald-500/20',
            'text' => 'text-emerald-700 dark:text-emerald-300',
            'iconBg' => 'bg-emerald-500',
            'shadow' => 'shadow-emerald-500/25',
        ],
        'closed' => [
            'label' => 'Closed',
            'description' => 'Ticket sudah ditutup',
            'count' => $counts['closed'],
            'border' => 'border-rose-200 dark:border-rose-500/20',
            'text' => 'text-rose-700 dark:text-rose-300',
            'iconBg' => 'bg-rose-500',
            'shadow' => 'shadow-rose-500/25',
        ],
    ];

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
            'accent' => 'border-l-sky-500',
            'icon' => 'bg-sky-50 text-sky-600 dark:bg-sky-500/10 dark:text-sky-300',
        ],
        'IN_PROGRESS' => [
            'label' => 'In Progress',
            'badge' => 'bg-amber-50 text-amber-700 ring-amber-200 dark:bg-amber-500/10 dark:text-amber-300 dark:ring-amber-500/20',
            'accent' => 'border-l-amber-500',
            'icon' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-300',
        ],
        'RESOLVED' => [
            'label' => 'Resolved',
            'badge' => 'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-500/20',
            'accent' => 'border-l-emerald-500',
            'icon' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300',
        ],
        'CLOSED' => [
            'label' => 'Closed',
            'badge' => 'bg-rose-50 text-rose-700 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-300 dark:ring-rose-500/20',
            'accent' => 'border-l-rose-500',
            'icon' => 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-300',
        ],
    ];

    $sortButtons = [
        'ticket_number' => 'Ticket Number',
        'title' => 'Judul',
        'priority' => 'Prioritas',
        'status' => 'Status',
        'created_at' => 'Dibuat',
    ];
@endphp

<div class="space-y-6" wire:poll.20s="checkNewTickets">
    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white md:text-3xl">
                {{ __('Tickets') }}
            </h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400 md:text-base">
                {{ __('Kelola, pantau, dan tindak lanjuti semua ticket dalam satu halaman') }}
            </p>
        </div>

        <flux:button
            :href="route('tickets.create')"
            wire:navigate
            icon="plus"
            class="h-11 rounded-2xl px-5 shadow-sm"
        >
            {{ __('Buat Ticket') }}
        </flux:button>
    </div>

    {{-- Status Cards --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
        @foreach ($statusCards as $card)
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
                            {{ $card['count'] }}
                        </p>
                    </div>
                </div>

                <p class="mt-6 text-sm text-zinc-500 dark:text-zinc-400">
                    {{ __($card['description']) }}
                </p>
            </div>
        @endforeach
    </div>

    {{-- Filter Panel --}}
    <div class="rounded-3xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div class="flex flex-col gap-4">
            <div class="flex flex-col gap-3 xl:flex-row xl:items-center">
                <div class="relative w-full xl:flex-1">
                    <flux:input
                        wire:model.live.debounce.300ms="search"
                        placeholder="{{ __('Cari ticket number atau judul...') }}"
                        class="h-12 rounded-2xl ps-11 dark:bg-zinc-950 dark:border-zinc-700/50 dark:text-white dark:placeholder-zinc-500"
                    />

                    <svg class="pointer-events-none absolute start-4 top-1/2 size-5 -translate-y-1/2 text-zinc-400"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <circle cx="11" cy="11" r="7" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="m20 20-3.5-3.5" />
                    </svg>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:w-[470px]">
                    <flux:select
                        wire:model.live="statusFilter"
                        class="h-12 rounded-2xl dark:bg-zinc-950 dark:border-zinc-700/50 dark:text-white"
                    >
                        <option value="">{{ __('Semua Status') }}</option>
                        <option value="OPEN">Open</option>
                        <option value="IN_PROGRESS">In Progress</option>
                        <option value="RESOLVED">Resolved</option>
                        <option value="CLOSED">Closed</option>
                    </flux:select>

                    <flux:select
                        wire:model.live="priorityFilter"
                        class="h-12 rounded-2xl dark:bg-zinc-950 dark:border-zinc-700/50 dark:text-white"
                    >
                        <option value="">{{ __('Semua Prioritas') }}</option>
                        <option value="LOW">Low</option>
                        <option value="MEDIUM">Medium</option>
                        <option value="HIGH">High</option>
                        <option value="URGENT">Urgent</option>
                    </flux:select>
                </div>

                <flux:button
                    wire:click="refresh"
                    icon="arrow-path"
                    variant="ghost"
                    class="h-12 justify-center rounded-2xl xl:w-auto"
                >
                    {{ __('Refresh') }}
                </flux:button>
            </div>

            <div class="border-t border-zinc-100 pt-4 dark:border-zinc-800">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
                    <span class="shrink-0 text-xs font-semibold uppercase tracking-[0.18em] text-zinc-500 dark:text-zinc-400">
                        {{ __('Urutkan') }}
                    </span>

                    <div class="-mx-1 flex gap-2 overflow-x-auto px-1 pb-1 lg:flex-wrap lg:overflow-visible lg:pb-0">
                        @foreach ($sortButtons as $field => $label)
                            <button
                                type="button"
                                wire:click="sortBy('{{ $field }}')"
                                class="shrink-0 rounded-2xl border px-4 py-2 text-sm font-medium transition
                                {{ $sortField === $field
                                    ? 'border-blue-500 bg-blue-600 text-white shadow-sm shadow-blue-600/20'
                                    : 'border-zinc-200 bg-white text-zinc-600 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-300 dark:hover:border-blue-500/40 dark:hover:bg-blue-500/10 dark:hover:text-blue-300' }}"
                            >
                                {{ __($label) }}
                                @if ($sortField === $field)
                                    <span class="ms-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- New Ticket Notice --}}
    @if ($hasNewTickets)
        <div class="flex items-center gap-3 rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4 text-sm text-blue-700 dark:border-blue-500/20 dark:bg-blue-500/10 dark:text-blue-300">
            <div class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z" />
                </svg>
            </div>
            <p class="font-medium">
                {{ __('Ada ticket baru. Halaman sudah diperbarui.') }}
            </p>
        </div>
    @endif

    {{-- Ticket List --}}
    <div class="overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div class="flex flex-col gap-2 border-b border-zinc-100 px-5 py-5 dark:border-zinc-800 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                    {{ __('Daftar Ticket') }}
                </h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    {{ __('Klik ticket untuk melihat detail dan melakukan tindakan lanjutan') }}
                </p>
            </div>

            <span class="inline-flex w-fit items-center rounded-2xl bg-zinc-50 px-3 py-1.5 text-xs font-semibold text-zinc-600 ring-1 ring-inset ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-700">
                {{ $tickets->total() }} {{ __('ticket') }}
            </span>
        </div>

        <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
            @forelse ($tickets as $ticket)
                @php
                    $status = $statusMeta[$ticket->status] ?? [
                        'label' => \Illuminate\Support\Str::headline($ticket->status),
                        'badge' => 'bg-zinc-50 text-zinc-700 ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-700',
                        'accent' => 'border-l-zinc-400',
                        'icon' => 'bg-zinc-50 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300',
                    ];

                    $priority = $priorityMeta[$ticket->priority] ?? [
                        'label' => \Illuminate\Support\Str::headline($ticket->priority),
                        'badge' => 'bg-zinc-50 text-zinc-700 ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-700',
                        'dot' => 'bg-zinc-500',
                    ];
                @endphp

                <a href="{{ route('tickets.show', $ticket) }}" wire:navigate class="group block">
                    <div class="border-l-4 {{ $status['accent'] }} px-5 py-5 transition hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div class="flex min-w-0 items-start gap-4">
                                <div class="flex size-13 shrink-0 items-center justify-center rounded-2xl {{ $status['icon'] }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h3m5 4H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5.586A2 2 0 0 1 14 4.586L18.414 9A2 2 0 0 1 19 10.414V18a2 2 0 0 1-2 2Z" />
                                    </svg>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="inline-flex items-center rounded-xl bg-zinc-100 px-3 py-1 font-mono text-xs font-semibold text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                                            {{ $ticket->ticket_number }}
                                        </span>

                                        <span class="inline-flex items-center gap-2 rounded-xl px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset {{ $priority['badge'] }}">
                                            <span class="size-1.5 rounded-full {{ $priority['dot'] }}"></span>
                                            {{ strtoupper($priority['label']) }}
                                        </span>
                                    </div>

                                    <h3 class="mt-2 line-clamp-1 text-base font-semibold text-zinc-900 transition group-hover:text-blue-700 dark:text-white dark:group-hover:text-blue-300">
                                        {{ $ticket->title }}
                                    </h3>

                                    <div class="mt-2 flex flex-wrap items-center gap-x-2 gap-y-1 text-sm text-zinc-500 dark:text-zinc-400">
                                        <span class="font-medium text-zinc-700 dark:text-zinc-300">
                                            {{ __('Dibuat') }}:
                                        </span>
                                        <span>{{ $ticket->created_at->format('d M Y, H:i') }}</span>
                                        <span class="hidden text-zinc-300 dark:text-zinc-600 sm:inline">•</span>
                                        <span>{{ $ticket->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-2 lg:justify-end">
                                <span class="inline-flex items-center gap-2 rounded-xl px-3 py-2 text-xs font-bold ring-1 ring-inset {{ $status['badge'] }}">
                                    <span class="size-2 rounded-full bg-current opacity-80"></span>
                                    {{ strtoupper($status['label']) }}
                                </span>

                                <span class="inline-flex items-center gap-2 rounded-xl bg-zinc-50 px-3 py-2 text-xs font-semibold text-zinc-500 ring-1 ring-inset ring-zinc-200 transition group-hover:text-blue-600 dark:bg-zinc-800 dark:text-zinc-400 dark:ring-zinc-700 dark:group-hover:text-blue-300">
                                    {{ __('Detail') }}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 transition group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 6 6 6-6 6" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="flex min-h-[320px] flex-col items-center justify-center px-6 py-12 text-center">
                    <div class="relative mb-6">
                        <div class="absolute inset-0 rounded-full bg-blue-400/20 blur-2xl dark:bg-blue-400/10"></div>
                        <div class="relative flex size-24 items-center justify-center rounded-[2rem] bg-gradient-to-br from-blue-100 to-cyan-100 text-blue-600 dark:from-blue-500/10 dark:to-cyan-500/10 dark:text-blue-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h3m5 4H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5.586A2 2 0 0 1 14 4.586L18.414 9A2 2 0 0 1 19 10.414V18a2 2 0 0 1-2 2Z" />
                            </svg>
                        </div>
                    </div>

                    <h3 class="text-xl font-semibold text-zinc-900 dark:text-white">
                        {{ __('Belum ada ticket') }}
                    </h3>
                    <p class="mt-2 max-w-md text-sm leading-6 text-zinc-500 dark:text-zinc-400">
                        {{ __('Ticket yang dibuat nanti akan muncul di sini. Coba ubah filter atau buat ticket baru.') }}
                    </p>

                    <flux:button :href="route('tickets.create')" wire:navigate icon="plus" class="mt-5 rounded-2xl">
                        {{ __('Buat Ticket') }}
                    </flux:button>
                </div>
            @endforelse
        </div>

        @if ($tickets->hasPages())
            <div class="border-t border-zinc-100 px-5 py-4 dark:border-zinc-800">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</div>