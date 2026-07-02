<div class="space-y-5" wire:poll.20s="checkNewTickets">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-semibold tracking-tight text-zinc-900 dark:text-white">{{ __('Tickets') }}</h1>
            <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Kelola dan pantau semua ticket') }}</p>
        </div>

        <flux:button :href="route('tickets.create')" wire:navigate icon="plus">
            {{ __('Buat Ticket') }}
        </flux:button>
    </div>

    {{-- 4 cards atas dibiarin --}}
    <div class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-4">
        <flux:card
            class="rounded-2xl border-2 border-sky-300 bg-sky-50 p-4 shadow-sm dark:border-sky-700 dark:bg-sky-950/20">
            <p class="text-xs font-semibold uppercase tracking-wide text-sky-800 dark:text-sky-300">
                {{ __('Open') }}
            </p>
            <p class="mt-2 text-3xl font-bold text-sky-700 dark:text-sky-400">
                {{ $counts['open'] }}
            </p>
            <p class="mt-1 text-xs text-sky-700/80 dark:text-sky-300/80">
                {{ __('Ticket baru / belum diproses') }}
            </p>
        </flux:card>

        <flux:card
            class="rounded-2xl border-2 border-amber-300 bg-amber-50 p-4 shadow-sm dark:border-amber-700 dark:bg-amber-950/20">
            <p class="text-xs font-semibold uppercase tracking-wide text-amber-800 dark:text-amber-300">
                {{ __('In Progress') }}
            </p>
            <p class="mt-2 text-3xl font-bold text-amber-700 dark:text-amber-400">
                {{ $counts['in_progress'] }}
            </p>
            <p class="mt-1 text-xs text-amber-700/80 dark:text-amber-300/80">
                {{ __('Ticket sedang dikerjakan') }}
            </p>
        </flux:card>

        <flux:card
            class="rounded-2xl border-2 border-emerald-300 bg-emerald-50 p-4 shadow-sm dark:border-emerald-700 dark:bg-emerald-950/20">
            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-800 dark:text-emerald-300">
                {{ __('Resolved') }}
            </p>
            <p class="mt-2 text-3xl font-bold text-emerald-700 dark:text-emerald-400">
                {{ $counts['resolved'] }}
            </p>
            <p class="mt-1 text-xs text-emerald-700/80 dark:text-emerald-300/80">
                {{ __('Ticket sudah diselesaikan') }}
            </p>
        </flux:card>

        <flux:card
            class="rounded-2xl border-2 border-rose-300 bg-rose-50 p-4 shadow-sm dark:border-rose-700 dark:bg-rose-950/20">
            <p class="text-xs font-semibold uppercase tracking-wide text-rose-800 dark:text-rose-300">
                {{ __('Closed') }}
            </p>
            <p class="mt-2 text-3xl font-bold text-rose-700 dark:text-rose-400">
                {{ $counts['closed'] }}
            </p>
            <p class="mt-1 text-xs text-rose-700/80 dark:text-rose-300/80">
                {{ __('Ticket sudah ditutup') }}
            </p>
        </flux:card>
    </div>

    {{-- filter/search area --}}
    <div class="rounded-2xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div class="space-y-4">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
                <div class="relative w-full lg:flex-1">
                    <flux:input wire:model.live.debounce.300ms="search"
                        placeholder="{{ __('Cari ticket number atau judul...') }}"
                        class="h-11 ps-10 dark:bg-zinc-900 dark:border-zinc-700/50 dark:text-white dark:placeholder-zinc-500" />

                    <svg class="pointer-events-none absolute start-3.5 top-1/2 size-4 -translate-y-1/2 text-zinc-400"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:w-[420px]">
                    <flux:select wire:model.live="statusFilter"
                        class="h-11 dark:bg-zinc-900 dark:border-zinc-700/50 dark:text-white">
                        <option value="">{{ __('Semua Status') }}</option>
                        <option value="OPEN">Open</option>
                        <option value="IN_PROGRESS">In Progress</option>
                        <option value="RESOLVED">Resolved</option>
                        <option value="CLOSED">Closed</option>
                    </flux:select>

                    <flux:select wire:model.live="priorityFilter"
                        class="h-11 dark:bg-zinc-900 dark:border-zinc-700/50 dark:text-white">
                        <option value="">{{ __('Semua Prioritas') }}</option>
                        <option value="LOW">Low</option>
                        <option value="MEDIUM">Medium</option>
                        <option value="HIGH">High</option>
                        <option value="URGENT">Urgent</option>
                    </flux:select>
                </div>

                <flux:button wire:click="refresh" icon="arrow-path" variant="ghost"
                    class="h-11 justify-center lg:w-auto">
                    {{ __('Refresh') }}
                </flux:button>
            </div>

            <div class="border-t border-zinc-100 pt-4 dark:border-zinc-800">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <span
                        class="shrink-0 text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        {{ __('Urutkan') }}
                    </span>

                    <div class="-mx-1 flex gap-2 overflow-x-auto px-1 pb-1 sm:flex-wrap sm:overflow-visible sm:pb-0">
                        <button wire:click="sortBy('ticket_number')"
                            class="shrink-0 rounded-full border px-3.5 py-2 text-sm font-medium transition
                            {{ $sortField === 'ticket_number'
                                ? 'border-sky-500 bg-sky-600 text-white shadow-sm'
                                : 'border-zinc-200 bg-white text-zinc-600 hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 dark:hover:border-sky-500/50 dark:hover:bg-sky-950/40 dark:hover:text-sky-300' }}">
                            {{ __('Ticket Number') }}
                            @if ($sortField === 'ticket_number')
                                <span class="ms-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </button>

                        <button wire:click="sortBy('title')"
                            class="shrink-0 rounded-full border px-3.5 py-2 text-sm font-medium transition
                            {{ $sortField === 'title'
                                ? 'border-sky-500 bg-sky-600 text-white shadow-sm'
                                : 'border-zinc-200 bg-white text-zinc-600 hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 dark:hover:border-sky-500/50 dark:hover:bg-sky-950/40 dark:hover:text-sky-300' }}">
                            {{ __('Judul') }}
                            @if ($sortField === 'title')
                                <span class="ms-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </button>

                        <button wire:click="sortBy('priority')"
                            class="shrink-0 rounded-full border px-3.5 py-2 text-sm font-medium transition
                            {{ $sortField === 'priority'
                                ? 'border-sky-500 bg-sky-600 text-white shadow-sm'
                                : 'border-zinc-200 bg-white text-zinc-600 hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 dark:hover:border-sky-500/50 dark:hover:bg-sky-950/40 dark:hover:text-sky-300' }}">
                            {{ __('Prioritas') }}
                            @if ($sortField === 'priority')
                                <span class="ms-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </button>

                        <button wire:click="sortBy('status')"
                            class="shrink-0 rounded-full border px-3.5 py-2 text-sm font-medium transition
                            {{ $sortField === 'status'
                                ? 'border-sky-500 bg-sky-600 text-white shadow-sm'
                                : 'border-zinc-200 bg-white text-zinc-600 hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 dark:hover:border-sky-500/50 dark:hover:bg-sky-950/40 dark:hover:text-sky-300' }}">
                            {{ __('Status') }}
                            @if ($sortField === 'status')
                                <span class="ms-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </button>

                        <button wire:click="sortBy('created_at')"
                            class="shrink-0 rounded-full border px-3.5 py-2 text-sm font-medium transition
                            {{ $sortField === 'created_at'
                                ? 'border-sky-500 bg-sky-600 text-white shadow-sm'
                                : 'border-zinc-200 bg-white text-zinc-600 hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 dark:hover:border-sky-500/50 dark:hover:bg-sky-950/40 dark:hover:text-sky-300' }}">
                            {{ __('Dibuat') }}
                            @if ($sortField === 'created_at')
                                <span class="ms-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($hasNewTickets)
        <div
            class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700 dark:border-blue-800/50 dark:bg-blue-950/40 dark:text-blue-300">
            {{ __('Ada ticket baru. Halaman diperbarui.') }}
        </div>
    @endif

    {{-- list style baru --}}
    <flux:card class="overflow-hidden border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div class="border-b border-zinc-200 px-5 py-4 dark:border-zinc-800">
            <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">{{ __('Daftar Ticket') }}</h2>
            {{-- <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('Tampilan dibuat lebih ringkas supaya user fokus ke informasi yang penting.') }}
            </p> --}}
        </div>

        <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
            @forelse ($tickets as $ticket)
                @php
                    $priorityClasses = match ($ticket->priority) {
                        'URGENT'
                            => 'bg-rose-100 text-rose-700 ring-rose-200 dark:bg-rose-950/50 dark:text-rose-300 dark:ring-rose-800/70',
                        'HIGH'
                            => 'bg-orange-100 text-orange-700 ring-orange-200 dark:bg-orange-950/50 dark:text-orange-300 dark:ring-orange-800/70',
                        'MEDIUM'
                            => 'bg-blue-100 text-blue-700 ring-blue-200 dark:bg-blue-950/50 dark:text-blue-300 dark:ring-blue-800/70',
                        'LOW'
                            => 'bg-slate-100 text-slate-700 ring-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700',
                        default
                            => 'bg-zinc-100 text-zinc-700 ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-700',
                    };

                    $statusClasses = match ($ticket->status) {
                        'OPEN'
                            => 'bg-sky-100 text-sky-700 ring-sky-200 dark:bg-sky-950/50 dark:text-sky-300 dark:ring-sky-800/70',

                        'IN_PROGRESS'
                            => 'bg-amber-100 text-amber-700 ring-amber-200 dark:bg-amber-950/50 dark:text-amber-300 dark:ring-amber-800/70',

                        'RESOLVED'
                            => 'bg-emerald-100 text-emerald-700 ring-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:ring-emerald-800/70',

                        'CLOSED'
                            => 'bg-rose-100 text-rose-700 ring-rose-200 dark:bg-rose-950/50 dark:text-rose-300 dark:ring-rose-800/70',

                        default
                            => 'bg-zinc-100 text-zinc-700 ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-700',
                    };

                    $rowAccent = match ($ticket->status) {
                        'OPEN' => 'border-l-sky-500',
                        'IN_PROGRESS' => 'border-l-amber-500',
                        'RESOLVED' => 'border-l-emerald-500',
                        'CLOSED' => 'border-l-rose-500',
                        default => 'border-l-zinc-400',
                    };

                    $iconWrapClasses = match ($ticket->status) {
                        'OPEN' => 'bg-sky-100 text-sky-700 dark:bg-sky-950/40 dark:text-sky-300',
                        'IN_PROGRESS' => 'bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300',
                        'RESOLVED' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300',
                        'CLOSED' => 'bg-rose-100 text-rose-700 dark:bg-rose-950/40 dark:text-rose-300',
                        default => 'bg-zinc-100 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300',
                    };
                @endphp

                <a href="{{ route('tickets.show', $ticket) }}" wire:navigate class="group block">
                    <div
                        class="border-l-4 {{ $rowAccent }} px-5 py-4 transition hover:bg-sky-50/60 dark:hover:bg-zinc-800/60">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div class="flex min-w-0 items-start gap-4">
                                {{-- icon penanda --}}
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl {{ $iconWrapClasses }}">
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12h6m-6 4h3m5 4H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5.586A2 2 0 0 1 14 4.586L18.414 9A2 2 0 0 1 19 10.414V18a2 2 0 0 1-2 2Z" />
                                    </svg>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span
                                            class="inline-flex items-center rounded-full bg-zinc-100 px-3 py-1 font-mono text-xs font-semibold text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                                            {{ $ticket->ticket_number }}
                                        </span>
                                    </div>

                                    <h3
                                        class="mt-2 line-clamp-1 text-base font-semibold text-zinc-900 transition group-hover:text-sky-700 dark:text-zinc-100 dark:group-hover:text-sky-300">
                                        {{ $ticket->title }}
                                    </h3>

                                    <div
                                        class="mt-2 flex flex-wrap items-center gap-x-2 gap-y-1 text-sm text-zinc-500 dark:text-zinc-400">
                                        <span
                                            class="font-medium text-zinc-600 dark:text-zinc-300">{{ __('Dibuat') }}:</span>
                                        <span>{{ $ticket->created_at->format('d M Y, H:i') }}</span>
                                        <span class="hidden text-zinc-300 dark:text-zinc-600 sm:inline">•</span>
                                        <span>{{ $ticket->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- chips info --}}
                            <div class="flex flex-wrap items-center gap-2 md:justify-end">
                                <span
                                    class="inline-flex items-center gap-2 rounded-full px-3 py-2 text-xs font-bold ring-1 ring-inset {{ $priorityClasses }}">
                                    <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M5 5v14l7-4 7 4V5H5Z" />
                                    </svg>
                                    <span class="uppercase tracking-wide">{{ $ticket->priority }}</span>
                                </span>

                                <span
                                    class="inline-flex items-center gap-2 rounded-full px-3 py-2 text-xs font-bold ring-1 ring-inset {{ $statusClasses }}">
                                    <span class="h-2 w-2 rounded-full bg-current opacity-80"></span>
                                    <span
                                        class="uppercase tracking-wide">{{ str_replace('_', ' ', $ticket->status) }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="px-5 py-12 text-center">
                    <div
                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
                        <svg class="h-6 w-6 text-zinc-400 dark:text-zinc-500" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h3m5 4H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5.586A2 2 0 0 1 14 4.586L18.414 9A2 2 0 0 1 19 10.414V18a2 2 0 0 1-2 2Z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Belum ada ticket') }}
                    </h3>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                        {{ __('Ticket yang dibuat nanti akan muncul di sini.') }}</p>
                </div>
            @endforelse
        </div>

        <div class="border-t border-zinc-200 px-4 py-3 dark:border-zinc-800">
            {{ $tickets->links() }}
        </div>
    </flux:card>
</div>
