<div class="space-y-5">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-semibold tracking-tight text-zinc-900 dark:text-white">{{ __('Dashboard') }}</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">{{ __('Overview ticket dan performa') }}</p>
        </div>
        <flux:select wire:model.live="period" class="max-w-36 dark:bg-zinc-900 dark:border-zinc-700/50 dark:text-white">
            <option value="all">{{ __('Semua Periode') }}</option>
            <option value="today">{{ __('Hari Ini') }}</option>
            <option value="week">{{ __('Minggu Ini') }}</option>
            <option value="month">{{ __('Bulan Ini') }}</option>
        </flux:select>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-3">
        <flux:card class="p-4 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">{{ __('Total') }}</p>
            <p class="mt-1 text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['total'] }}</p>
        </flux:card>
        <flux:card class="p-4 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">{{ __('Open') }}</p>
            <p class="mt-1 text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $stats['open'] }}</p>
        </flux:card>
        <flux:card class="p-4 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">{{ __('Assigned') }}</p>
            <p class="mt-1 text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['assigned'] }}</p>
        </flux:card>
        <flux:card class="p-4 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">{{ __('In Progress') }}</p>
            <p class="mt-1 text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['in_progress'] }}</p>
        </flux:card>
        <flux:card class="p-4 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">{{ __('Waiting User') }}</p>
            <p class="mt-1 text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $stats['waiting_user'] }}</p>
        </flux:card>
        <flux:card class="p-4 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">{{ __('Resolved') }}</p>
            <p class="mt-1 text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['resolved'] }}</p>
        </flux:card>
        <flux:card class="p-4 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">{{ __('Closed') }}</p>
            <p class="mt-1 text-2xl font-bold text-zinc-600 dark:text-zinc-400">{{ $stats['closed'] }}</p>
        </flux:card>
    </div>

    {{-- Widget Row 1 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <flux:card class="p-5 border-l-[3px] border-l-violet-500 dark:border-l-violet-400 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-white mb-4">{{ __('Tiket per Prioritas') }}</h2>
            <div class="space-y-3">
                @php $maxPrior = max($statsByPriority) ?: 1; @endphp
                @foreach (['URGENT' => 'red', 'HIGH' => 'orange', 'MEDIUM' => 'blue', 'LOW' => 'slate'] as $label => $color)
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-zinc-600 dark:text-zinc-400 w-16 shrink-0">{{ $label }}</span>
                        <div class="flex-1 h-2 rounded-full bg-zinc-100 dark:bg-zinc-800">
                            <div class="h-2 rounded-full bg-{{ $color }}-500" style="width: {{ ($statsByPriority[$label] / $maxPrior) * 100 }}%"></div>
                        </div>
                        <span class="text-sm font-semibold text-zinc-900 dark:text-white w-8 text-right">{{ $statsByPriority[$label] }}</span>
                    </div>
                @endforeach
            </div>
        </flux:card>

        <flux:card class="p-5 border-l-[3px] border-l-blue-500 dark:border-l-blue-400 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-white mb-4">{{ __('Tiket per Departemen') }}</h2>
            <div class="space-y-2">
                @forelse ($perDepartemen as $dept)
                    <div class="flex items-center justify-between py-1.5 border-b border-zinc-100 dark:border-zinc-800 last:border-0">
                        <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ $dept->name }}</span>
                        <span class="text-sm font-semibold text-zinc-900 dark:text-white">{{ $dept->tickets_count }}</span>
                    </div>
                @empty
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Belum ada data') }}</p>
                @endforelse
            </div>
        </flux:card>
    </div>

    {{-- Widget Row 2 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <flux:card class="p-5 border-l-[3px] border-l-emerald-500 dark:border-l-emerald-400 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-white mb-4">{{ __('Tiket per Kategori') }}</h2>
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @forelse ($perKategori as $cat)
                    <div class="flex items-center justify-between py-1.5 border-b border-zinc-100 dark:border-zinc-800 last:border-0">
                        <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ $cat->name }}</span>
                        <span class="text-sm font-semibold text-zinc-900 dark:text-white">{{ $cat->tickets_count }}</span>
                    </div>
                @empty
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Belum ada data') }}</p>
                @endforelse
            </div>
        </flux:card>

        <flux:card class="p-5 border-l-[3px] border-l-cyan-500 dark:border-l-cyan-400 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Tiket Saya (Active)') }}</h2>
                @if ($myTickets->isNotEmpty())
                    <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ $myTickets->count() }} tiket</span>
                @endif
            </div>
            <div class="space-y-1 max-h-80 overflow-y-auto">
                @forelse ($myTickets as $ticket)
                    <a href="{{ route('tickets.show', $ticket) }}" wire:navigate class="flex items-center justify-between py-2 px-2 -mx-2 rounded hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-zinc-900 dark:text-white truncate">{{ $ticket->title }}</p>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $ticket->ticket_number }} &middot; {{ $ticket->requester_name ?: $ticket->requester?->name }}</p>
                        </div>
                        <div class="flex gap-1.5 shrink-0 ml-3">
                            <flux:badge color="{{ $ticket->priority === 'URGENT' ? 'red' : ($ticket->priority === 'HIGH' ? 'orange' : ($ticket->priority === 'MEDIUM' ? 'blue' : 'slate')) }}" size="sm" class="font-medium">
                                {{ $ticket->priority }}
                            </flux:badge>
                        </div>
                    </a>
                @empty
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Tidak ada tiket aktif') }}</p>
                @endforelse
            </div>
        </flux:card>
    </div>

    {{-- Needs Action --}}
    @if ($needsAction->isNotEmpty())
        <flux:card class="p-5 border-l-[3px] border-l-red-500 dark:border-l-red-400 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
            <div class="flex items-center gap-2 mb-4">
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Butuh Tindakan') }}</h2>
                <flux:badge color="red" size="sm">{{ $needsAction->count() }}</flux:badge>
            </div>
            <div class="space-y-1">
                @foreach ($needsAction as $ticket)
                    <a href="{{ route('tickets.show', $ticket) }}" wire:navigate class="flex items-center justify-between py-2 px-2 -mx-2 rounded hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-zinc-900 dark:text-white truncate">{{ $ticket->title }}</p>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                {{ $ticket->ticket_number }} &middot; {{ $ticket->requester_name ?: $ticket->requester?->name }}
                                @if ($ticket->activeAssignment?->assignedTo)
                                    &middot; {{ __('Assigned ke') }} {{ $ticket->activeAssignment->assignedTo->name }}
                                @endif
                            </p>
                        </div>
                        <div class="flex gap-1.5 shrink-0 ml-3">
                            <flux:badge color="{{ $ticket->priority === 'URGENT' ? 'red' : 'orange' }}" size="sm" class="font-medium">
                                {{ $ticket->priority }}
                            </flux:badge>
                            <flux:badge color="{{ $ticket->status === 'WAITING_USER' ? 'orange' : 'green' }}" size="sm" class="font-medium">
                                {{ str_replace('_', ' ', $ticket->status) }}
                            </flux:badge>
                        </div>
                    </a>
                @endforeach
            </div>
        </flux:card>
    @endif

    {{-- Recent Tickets --}}
    <flux:card class="overflow-hidden border-l-[3px] border-l-zinc-400/50 dark:border-l-zinc-500/50 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
        <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Ticket Terbaru') }}</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-100 dark:divide-zinc-800">
                <thead>
                    <tr class="border-b border-zinc-100 dark:border-zinc-800">
                        <th class="px-5 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Ticket') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Judul') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Requester') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Prioritas') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Status') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Dibuat') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50 dark:divide-zinc-800/50">
                    @forelse ($recentTickets as $ticket)
                        <tr onclick="window.location='{{ route('tickets.show', $ticket) }}'" class="cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-5 py-3 font-mono text-xs text-zinc-500 dark:text-zinc-400">{{ $ticket->ticket_number }}</td>
                            <td class="px-5 py-3 text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $ticket->title }}</td>
                            <td class="px-5 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $ticket->requester_name ?: $ticket->requester?->name }}</td>
                            <td class="px-5 py-3">
                                <flux:badge color="{{ $ticket->priority === 'URGENT' ? 'red' : ($ticket->priority === 'HIGH' ? 'orange' : ($ticket->priority === 'MEDIUM' ? 'blue' : 'slate')) }}" size="sm" class="font-medium">
                                    {{ $ticket->priority }}
                                </flux:badge>
                            </td>
                            <td class="px-5 py-3">
                                <flux:badge color="{{ $ticket->status === 'OPEN' ? 'green' : ($ticket->status === 'CLOSED' ? 'zinc' : 'yellow') }}" size="sm" class="font-medium">
                                    {{ str_replace('_', ' ', $ticket->status) }}
                                </flux:badge>
                            </td>
                            <td class="px-5 py-3 text-xs text-zinc-400 dark:text-zinc-500">{{ $ticket->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-sm text-zinc-400 dark:text-zinc-500">{{ __('Belum ada ticket') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </flux:card>
</div>
