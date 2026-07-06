@php
    $user = auth()->user();

    $maxPriority = max(array_values($statsByPriority)) ?: 1;

    $priorityMeta = [
        'URGENT' => [
            'label' => 'Urgent',
            'dot' => 'bg-rose-500',
            'bar' => 'bg-rose-500',
            'soft' => 'bg-rose-50 dark:bg-rose-500/10',
            'text' => 'text-rose-700 dark:text-rose-300',
            'badge' => 'bg-rose-50 text-rose-700 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-300 dark:ring-rose-500/20',
        ],
        'HIGH' => [
            'label' => 'High',
            'dot' => 'bg-amber-500',
            'bar' => 'bg-amber-500',
            'soft' => 'bg-amber-50 dark:bg-amber-500/10',
            'text' => 'text-amber-700 dark:text-amber-300',
            'badge' => 'bg-amber-50 text-amber-700 ring-amber-200 dark:bg-amber-500/10 dark:text-amber-300 dark:ring-amber-500/20',
        ],
        'MEDIUM' => [
            'label' => 'Medium',
            'dot' => 'bg-blue-500',
            'bar' => 'bg-blue-500',
            'soft' => 'bg-blue-50 dark:bg-blue-500/10',
            'text' => 'text-blue-700 dark:text-blue-300',
            'badge' => 'bg-blue-50 text-blue-700 ring-blue-200 dark:bg-blue-500/10 dark:text-blue-300 dark:ring-blue-500/20',
        ],
        'LOW' => [
            'label' => 'Low',
            'dot' => 'bg-emerald-500',
            'bar' => 'bg-emerald-500',
            'soft' => 'bg-emerald-50 dark:bg-emerald-500/10',
            'text' => 'text-emerald-700 dark:text-emerald-300',
            'badge' => 'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-500/20',
        ],
    ];

    $statusMeta = [
        'OPEN' => [
            'label' => 'Open',
            'badge' => 'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-500/20',
        ],
        'IN_PROGRESS' => [
            'label' => 'In Progress',
            'badge' => 'bg-amber-50 text-amber-700 ring-amber-200 dark:bg-amber-500/10 dark:text-amber-300 dark:ring-amber-500/20',
        ],
        'RESOLVED' => [
            'label' => 'Resolved',
            'badge' => 'bg-violet-50 text-violet-700 ring-violet-200 dark:bg-violet-500/10 dark:text-violet-300 dark:ring-violet-500/20',
        ],
        'CLOSED' => [
            'label' => 'Closed',
            'badge' => 'bg-rose-50 text-rose-700 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-300 dark:ring-rose-500/20',
        ],
    ];
@endphp

<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white md:text-3xl">
                {{ __('Selamat datang, :name 👋', ['name' => $user?->name ?? 'Admin']) }}
            </h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400 md:text-base">
                {{ __('Berikut adalah ringkasan tiket dan performa sistem') }}
            </p>
        </div>

        <div class="w-full lg:w-auto">
            <div class="inline-flex w-full items-center gap-3 rounded-2xl border border-zinc-200 bg-white px-4 py-3 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 lg:w-auto">
                <div class="flex size-10 items-center justify-center rounded-xl bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z" />
                    </svg>
                </div>

                <div class="min-w-[180px]">
                    <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        {{ __('Filter Periode') }}
                    </label>

                    <select
                        wire:model.live="period"
                        class="w-full border-0 bg-transparent p-0 pr-8 text-sm font-medium text-zinc-900 outline-none focus:ring-0 dark:text-white"
                    >
                        <option value="all">{{ __('Semua Periode') }}</option>
                        <option value="today">{{ __('Hari Ini') }}</option>
                        <option value="week">{{ __('Minggu Ini') }}</option>
                        <option value="month">{{ __('Bulan Ini') }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
        {{-- Total --}}
        <div class="rounded-3xl border border-blue-200/70 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-blue-500/20 dark:bg-zinc-900">
            <div class="flex items-start justify-between gap-4">
                <div class="flex size-14 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-600/25">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 3.75h6.75a3 3 0 0 1 3 3v13.5l-3.75-2.25-3.75 2.25V6.75a3 3 0 0 0-3-3Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 3.75h8.25" />
                    </svg>
                </div>

                <div class="text-right">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-600 dark:text-blue-400">
                        {{ __('Total Tiket') }}
                    </p>
                    <p class="mt-1 text-4xl font-bold leading-none text-zinc-900 dark:text-white">
                        {{ $stats['total'] }}
                    </p>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Semua tiket') }}</p>
                <span class="text-blue-500 dark:text-blue-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 6 6 6-6 6" />
                    </svg>
                </span>
            </div>
        </div>

        {{-- Open --}}
        <div class="rounded-3xl border border-emerald-200/70 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-emerald-500/20 dark:bg-zinc-900">
            <div class="flex items-start justify-between gap-4">
                <div class="flex size-14 items-center justify-center rounded-2xl bg-emerald-500 text-white shadow-lg shadow-emerald-500/25">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m3 11.25 18-7.5-7.5 18-2.25-6L3 11.25Z" />
                    </svg>
                </div>

                <div class="text-right">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-600 dark:text-emerald-400">
                        {{ __('Open') }}
                    </p>
                    <p class="mt-1 text-4xl font-bold leading-none text-zinc-900 dark:text-white">
                        {{ $stats['open'] }}
                    </p>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Tiket baru') }}</p>
                <span class="text-emerald-500 dark:text-emerald-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 6 6 6-6 6" />
                    </svg>
                </span>
            </div>
        </div>

        {{-- In Progress --}}
        <div class="rounded-3xl border border-amber-200/70 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-amber-500/20 dark:bg-zinc-900">
            <div class="flex items-start justify-between gap-4">
                <div class="flex size-14 items-center justify-center rounded-2xl bg-amber-500 text-white shadow-lg shadow-amber-500/25">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m-9.75 5.25h11.5A2.25 2.25 0 0 0 20 17.25V6.75A2.25 2.25 0 0 0 17.75 4.5H6.25A2.25 2.25 0 0 0 4 6.75v10.5A2.25 2.25 0 0 0 6.25 19.5Z" />
                    </svg>
                </div>

                <div class="text-right">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-600 dark:text-amber-400">
                        {{ __('In Progress') }}
                    </p>
                    <p class="mt-1 text-4xl font-bold leading-none text-zinc-900 dark:text-white">
                        {{ $stats['in_progress'] }}
                    </p>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Sedang dikerjakan') }}</p>
                <span class="text-amber-500 dark:text-amber-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 6 6 6-6 6" />
                    </svg>
                </span>
            </div>
        </div>

        {{-- Resolved --}}
        <div class="rounded-3xl border border-violet-200/70 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-violet-500/20 dark:bg-zinc-900">
            <div class="flex items-start justify-between gap-4">
                <div class="flex size-14 items-center justify-center rounded-2xl bg-violet-600 text-white shadow-lg shadow-violet-600/25">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                </div>

                <div class="text-right">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-violet-600 dark:text-violet-400">
                        {{ __('Resolved') }}
                    </p>
                    <p class="mt-1 text-4xl font-bold leading-none text-zinc-900 dark:text-white">
                        {{ $stats['resolved'] }}
                    </p>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Tiket selesai') }}</p>
                <span class="text-violet-500 dark:text-violet-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 6 6 6-6 6" />
                    </svg>
                </span>
            </div>
        </div>

        {{-- Closed --}}
        <div class="rounded-3xl border border-rose-200/70 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-rose-500/20 dark:bg-zinc-900">
            <div class="flex items-start justify-between gap-4">
                <div class="flex size-14 items-center justify-center rounded-2xl bg-rose-500 text-white shadow-lg shadow-rose-500/25">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-1.5 0h12a1.5 1.5 0 0 1 1.5 1.5v7.5A1.5 1.5 0 0 1 18 21H6a1.5 1.5 0 0 1-1.5-1.5V12A1.5 1.5 0 0 1 6 10.5Z" />
                    </svg>
                </div>

                <div class="text-right">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-rose-600 dark:text-rose-400">
                        {{ __('Closed') }}
                    </p>
                    <p class="mt-1 text-4xl font-bold leading-none text-zinc-900 dark:text-white">
                        {{ $stats['closed'] }}
                    </p>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Tiket ditutup') }}</p>
                <span class="text-rose-500 dark:text-rose-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 6 6 6-6 6" />
                    </svg>
                </span>
            </div>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">
        {{-- Tiket per Prioritas --}}
        <div class="overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="flex items-center gap-3 border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
                <div class="flex size-10 items-center justify-center rounded-xl bg-blue-600 text-white shadow-lg shadow-blue-600/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25A2.25 2.25 0 0 1 6 3h10.5a2.25 2.25 0 0 1 2.25 2.25v13.5L15 16.5l-3.75 2.25L7.5 16.5l-3.75 2.25V5.25Z" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-blue-600 dark:text-blue-400">{{ __('Tiket per Prioritas') }}</h2>
            </div>

            <div class="space-y-5 p-5">
                @foreach ($priorityMeta as $key => $meta)
                    <div class="flex items-center gap-4">
                        <div class="flex min-w-[110px] items-center gap-3">
                            <span class="size-2.5 rounded-full {{ $meta['dot'] }}"></span>
                            <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $meta['label'] }}</span>
                        </div>

                        <div class="h-3 flex-1 overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800">
                            <div
                                class="h-full rounded-full {{ $meta['bar'] }}"
                                style="width: {{ ($statsByPriority[$key] / $maxPriority) * 100 }}%"
                            ></div>
                        </div>

                        <div class="flex min-w-[36px] justify-end">
                            <span class="inline-flex items-center rounded-xl bg-zinc-50 px-3 py-1 text-sm font-semibold text-zinc-700 ring-1 ring-inset ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-200 dark:ring-zinc-700">
                                {{ $statsByPriority[$key] }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Tiket per Departemen --}}
        <div class="overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="flex items-center gap-3 border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
                <div class="flex size-10 items-center justify-center rounded-xl bg-violet-600 text-white shadow-lg shadow-violet-600/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15m-13.5 0v18m11.5-18v18M8.25 7.5h7.5m-7.5 4.5h7.5m-7.5 4.5h7.5" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-violet-600 dark:text-violet-400">{{ __('Tiket per Departemen') }}</h2>
            </div>

            <div class="space-y-2 p-5">
                @forelse ($perDepartemen as $dept)
                    <div class="flex items-center justify-between rounded-2xl px-3 py-2.5 transition hover:bg-zinc-50 dark:hover:bg-zinc-800/60">
                        <div class="flex min-w-0 items-center gap-3">
                            <div class="flex size-8 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M4.5 18V7.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V18m0 0h6m-6 0H4.5m10.5 0V4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V18m0 0H15" />
                                </svg>
                            </div>
                            <span class="truncate text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $dept->name }}</span>
                        </div>

                        <span class="inline-flex min-w-9 items-center justify-center rounded-xl bg-zinc-50 px-3 py-1 text-sm font-semibold text-zinc-700 ring-1 ring-inset ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-200 dark:ring-zinc-700">
                            {{ $dept->tickets_count }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Belum ada data') }}</p>
                @endforelse
            </div>
        </div>

        {{-- Tiket per Kategori --}}
        <div class="overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="flex items-center gap-3 border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
                <div class="flex size-10 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-lg shadow-emerald-600/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 3.75h9A2.25 2.25 0 0 1 18.75 6v12A2.25 2.25 0 0 1 16.5 20.25h-9A2.25 2.25 0 0 1 5.25 18V6A2.25 2.25 0 0 1 7.5 3.75Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25h6m-6 4.5h6m-6 4.5h3" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-emerald-600 dark:text-emerald-400">{{ __('Tiket per Kategori') }}</h2>
            </div>

            <div class="max-h-[380px] space-y-2 overflow-y-auto p-5">
                @forelse ($perKategori as $cat)
                    <div class="flex items-center justify-between rounded-2xl px-3 py-2.5 transition hover:bg-zinc-50 dark:hover:bg-zinc-800/60">
                        <div class="flex min-w-0 items-center gap-3">
                            <div class="flex size-8 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 7.5 12 3.75 18.75 7.5 12 11.25 5.25 7.5Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 12 12 15.75 18.75 12" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 16.5 12 20.25 18.75 16.5" />
                                </svg>
                            </div>
                            <span class="truncate text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $cat->name }}</span>
                        </div>

                        <span class="inline-flex min-w-9 items-center justify-center rounded-xl bg-zinc-50 px-3 py-1 text-sm font-semibold text-zinc-700 ring-1 ring-inset ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-200 dark:ring-zinc-700">
                            {{ $cat->tickets_count }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Belum ada data') }}</p>
                @endforelse
            </div>
        </div>

        {{-- Tiket Saya --}}
        <div class="overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="flex items-center justify-between gap-3 border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 items-center justify-center rounded-xl bg-cyan-600 text-white shadow-lg shadow-cyan-600/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.964 0A9 9 0 1 0 6.018 18.725m11.964 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-cyan-600 dark:text-cyan-400">{{ __('Tiket Saya (Active)') }}</h2>
                </div>

                @if ($myTickets->isNotEmpty())
                    <span class="rounded-xl bg-zinc-50 px-3 py-1 text-xs font-semibold text-zinc-600 ring-1 ring-inset ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-700">
                        {{ $myTickets->count() }} {{ __('tiket') }}
                    </span>
                @endif
            </div>

            @if ($myTickets->isEmpty())
                <div class="flex min-h-[380px] flex-col items-center justify-center px-6 py-10 text-center">
                    <div class="relative mb-6">
                        <div class="absolute inset-0 rounded-full bg-cyan-400/20 blur-2xl dark:bg-cyan-400/10"></div>
                        <div class="relative flex size-24 items-center justify-center rounded-[2rem] bg-gradient-to-br from-cyan-100 to-teal-100 text-cyan-600 dark:from-cyan-500/10 dark:to-teal-500/10 dark:text-cyan-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6.75V6a4.5 4.5 0 0 0-9 0v.75m-1.5 0h12a1.5 1.5 0 0 1 1.5 1.5V18a1.5 1.5 0 0 1-1.5 1.5H6A1.5 1.5 0 0 1 4.5 18V8.25a1.5 1.5 0 0 1 1.5-1.5Z" />
                            </svg>
                        </div>
                    </div>

                    <h3 class="text-2xl font-semibold text-zinc-900 dark:text-white">
                        {{ __('Tidak ada tiket aktif') }}
                    </h3>
                    <p class="mt-2 max-w-sm text-sm leading-6 text-zinc-500 dark:text-zinc-400">
                        {{ __('Semua tiket Anda telah diselesaikan.') }}
                    </p>
                </div>
            @else
                <div class="max-h-[380px] space-y-3 overflow-y-auto p-5">
                    @foreach ($myTickets as $ticket)
                        <a
                            href="{{ route('tickets.show', $ticket) }}"
                            wire:navigate
                            class="block rounded-2xl border border-zinc-200 bg-zinc-50 p-4 transition hover:border-cyan-300 hover:bg-white hover:shadow-sm dark:border-zinc-800 dark:bg-zinc-950/40 dark:hover:border-cyan-500/30 dark:hover:bg-zinc-950"
                        >
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-zinc-900 dark:text-white">
                                        {{ $ticket->title }}
                                    </p>
                                    <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                                        {{ $ticket->ticket_number }} &middot;
                                        {{ $ticket->requester_name ?: $ticket->requester?->name }}
                                    </p>
                                </div>

                                <span class="inline-flex shrink-0 items-center rounded-xl px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset {{ $priorityMeta[$ticket->priority]['badge'] ?? $priorityMeta['LOW']['badge'] }}">
                                    {{ $priorityMeta[$ticket->priority]['label'] ?? $ticket->priority }}
                                </span>
                            </div>

                            <div class="mt-3 flex items-center justify-between text-xs text-zinc-500 dark:text-zinc-400">
                                <span>{{ $statusMeta[$ticket->status]['label'] ?? \Illuminate\Support\Str::headline($ticket->status) }}</span>
                                <span>{{ $ticket->created_at?->diffForHumans() }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Needs Action --}}
    @if ($needsAction->isNotEmpty())
        <div class="overflow-hidden rounded-3xl border border-rose-200 bg-white shadow-sm dark:border-rose-500/20 dark:bg-zinc-900">
            <div class="flex items-center justify-between gap-3 border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 items-center justify-center rounded-xl bg-rose-500 text-white shadow-lg shadow-rose-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008Zm9-3.278c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9Z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-rose-600 dark:text-rose-400">{{ __('Butuh Tindakan') }}</h2>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ __('Tiket urgent yang perlu perhatian cepat') }}</p>
                    </div>
                </div>

                <span class="inline-flex items-center rounded-xl bg-rose-50 px-3 py-1 text-sm font-semibold text-rose-700 ring-1 ring-inset ring-rose-200 dark:bg-rose-500/10 dark:text-rose-300 dark:ring-rose-500/20">
                    {{ $needsAction->count() }}
                </span>
            </div>

            <div class="space-y-3 p-5">
                @foreach ($needsAction as $ticket)
                    <a
                        href="{{ route('tickets.show', $ticket) }}"
                        wire:navigate
                        class="flex flex-col gap-3 rounded-2xl border border-zinc-200 bg-zinc-50 p-4 transition hover:border-rose-300 hover:bg-white hover:shadow-sm dark:border-zinc-800 dark:bg-zinc-950/40 dark:hover:border-rose-500/30 dark:hover:bg-zinc-950 md:flex-row md:items-center md:justify-between"
                    >
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-zinc-900 dark:text-white">
                                {{ $ticket->title }}
                            </p>
                            <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                                {{ $ticket->ticket_number }}
                                &middot;
                                {{ $ticket->requester_name ?: $ticket->requester?->name }}
                                @if ($ticket->activeAssignment?->assignedTo)
                                    &middot; {{ __('Assigned ke') }} {{ $ticket->activeAssignment->assignedTo->name }}
                                @endif
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center rounded-xl px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset {{ $priorityMeta[$ticket->priority]['badge'] ?? $priorityMeta['LOW']['badge'] }}">
                                {{ $priorityMeta[$ticket->priority]['label'] ?? $ticket->priority }}
                            </span>

                            <span class="inline-flex items-center rounded-xl px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset {{ $statusMeta[$ticket->status]['badge'] ?? $statusMeta['OPEN']['badge'] }}">
                                {{ $statusMeta[$ticket->status]['label'] ?? \Illuminate\Support\Str::headline($ticket->status) }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Recent Tickets --}}
    <div class="overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div class="flex items-center justify-between gap-3 border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
            <div>
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ __('Ticket Terbaru') }}</h2>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ __('Daftar tiket terbaru yang masuk ke sistem') }}</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-zinc-50/80 dark:bg-zinc-950/60">
                    <tr class="border-b border-zinc-100 dark:border-zinc-800">
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Ticket') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Judul') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Requester') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Prioritas') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Status') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Dibuat') }}</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse ($recentTickets as $ticket)
                        <tr
                            onclick="window.location='{{ route('tickets.show', $ticket) }}'"
                            class="cursor-pointer transition hover:bg-zinc-50 dark:hover:bg-zinc-800/40"
                        >
                            <td class="px-5 py-4 text-xs font-medium text-zinc-500 dark:text-zinc-400">
                                {{ $ticket->ticket_number }}
                            </td>
                            <td class="px-5 py-4 text-sm font-semibold text-zinc-900 dark:text-white">
                                {{ $ticket->title }}
                            </td>
                            <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                                {{ $ticket->requester_name ?: $ticket->requester?->name }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center rounded-xl px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset {{ $priorityMeta[$ticket->priority]['badge'] ?? $priorityMeta['LOW']['badge'] }}">
                                    {{ $priorityMeta[$ticket->priority]['label'] ?? $ticket->priority }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center rounded-xl px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset {{ $statusMeta[$ticket->status]['badge'] ?? $statusMeta['OPEN']['badge'] }}">
                                    {{ $statusMeta[$ticket->status]['label'] ?? \Illuminate\Support\Str::headline($ticket->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-xs text-zinc-500 dark:text-zinc-400">
                                {{ $ticket->created_at->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                {{ __('Belum ada ticket') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>