<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-semibold tracking-tight text-zinc-900 dark:text-white">{{ __('Tickets') }}</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">{{ __('Kelola dan pantau semua ticket') }}</p>
        </div>
        <flux:button :href="route('tickets.create')" wire:navigate icon="plus">
            {{ __('Buat Ticket') }}
        </flux:button>
    </div>

    <div class="grid grid-cols-4 gap-3">
        <flux:card class="p-4 border-l-[3px] border-l-emerald-500 dark:border-l-emerald-400 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">{{ __('Open') }}</p>
            <p class="mt-1 text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $counts['open'] }}</p>
        </flux:card>
        <flux:card class="p-4 border-l-[3px] border-l-blue-500 dark:border-l-blue-400 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">{{ __('Assigned') }}</p>
            <p class="mt-1 text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $counts['assigned'] }}</p>
        </flux:card>
        <flux:card class="p-4 border-l-[3px] border-l-yellow-500 dark:border-l-yellow-400 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">{{ __('In Progress') }}</p>
            <p class="mt-1 text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $counts['in_progress'] }}</p>
        </flux:card>
        <flux:card class="p-4 border-l-[3px] border-l-zinc-400 dark:border-l-zinc-500 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">{{ __('Closed') }}</p>
            <p class="mt-1 text-2xl font-bold text-zinc-600 dark:text-zinc-400">{{ $counts['closed'] }}</p>
        </flux:card>
    </div>

    <div class="flex items-center gap-3 flex-wrap">
        <div class="relative flex-1 max-w-sm">
            <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Cari ticket...') }}" class="ps-9 dark:bg-zinc-900 dark:border-zinc-700/50 dark:text-white dark:placeholder-zinc-500" />
            <svg class="pointer-events-none absolute start-3 top-1/2 -translate-y-1/2 size-4 text-zinc-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        </div>
        <flux:select wire:model.live="statusFilter" class="max-w-36 dark:bg-zinc-900 dark:border-zinc-700/50 dark:text-white">
            <option value="">{{ __('Semua Status') }}</option>
            <option value="OPEN">Open</option>
            <option value="ASSIGNED">Assigned</option>
            <option value="IN_PROGRESS">In Progress</option>
            <option value="WAITING_USER">Waiting User</option>
            <option value="RESOLVED">Resolved</option>
            <option value="CLOSED">Closed</option>
        </flux:select>
        <flux:select wire:model.live="priorityFilter" class="max-w-36 dark:bg-zinc-900 dark:border-zinc-700/50 dark:text-white">
            <option value="">{{ __('Semua Prioritas') }}</option>
            <option value="LOW">Low</option>
            <option value="MEDIUM">Medium</option>
            <option value="HIGH">High</option>
            <option value="URGENT">Urgent</option>
        </flux:select>
        <flux:button wire:click="refresh" icon="arrow-path" variant="ghost" class="shrink-0">{{ __('Refresh') }}</flux:button>
    </div>

    @if ($hasNewTickets)
        <div class="rounded-lg bg-blue-50 dark:bg-blue-950/50 border border-blue-200 dark:border-blue-800/50 px-4 py-3 text-sm text-blue-700 dark:text-blue-300">
            {{ __('Ada ticket baru. Halaman diperbarui.') }}
        </div>
    @endif

    <flux:card class="overflow-hidden dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-100 dark:divide-zinc-800">
                <thead>
                    <tr class="border-b border-zinc-100 dark:border-zinc-800">
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider cursor-pointer select-none" wire:click="sortBy('ticket_number')">
                            <span class="flex items-center gap-1"># @if ($sortField === 'ticket_number')<span class="text-xs">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>@endif</span>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider cursor-pointer select-none" wire:click="sortBy('title')">
                            <span class="flex items-center gap-1">{{ __('Judul') }} @if ($sortField === 'title')<span class="text-xs">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>@endif</span>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Requester') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Departemen') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Kategori') }}</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Lamp') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider cursor-pointer select-none" wire:click="sortBy('priority')">
                            <span class="flex items-center gap-1">{{ __('Prioritas') }} @if ($sortField === 'priority')<span class="text-xs">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>@endif</span>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider cursor-pointer select-none" wire:click="sortBy('status')">
                            <span class="flex items-center gap-1">{{ __('Status') }} @if ($sortField === 'status')<span class="text-xs">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>@endif</span>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider cursor-pointer select-none" wire:click="sortBy('created_at')">
                            <span class="flex items-center gap-1">{{ __('Dibuat') }} @if ($sortField === 'created_at')<span class="text-xs">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>@endif</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50 dark:divide-zinc-800/50">
                    @forelse ($tickets as $ticket)
                        <tr onclick="window.location='{{ route('tickets.show', $ticket) }}'" class="cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-4 py-3 font-mono text-xs text-zinc-500 dark:text-zinc-400">{{ $ticket->ticket_number }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $ticket->title }}</td>
                            <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $ticket->requester_name ?: $ticket->requester?->name }}</td>
                            <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $ticket->department?->name }}</td>
                            <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $ticket->category?->name }}</td>
                            <td class="px-4 py-3 text-center text-xs text-zinc-400 dark:text-zinc-500">{{ $ticket->attachments_count ?: '' }}</td>
                            <td class="px-4 py-3">
                                <flux:badge color="{{ $ticket->priority === 'URGENT' ? 'red' : ($ticket->priority === 'HIGH' ? 'orange' : ($ticket->priority === 'MEDIUM' ? 'blue' : 'slate')) }}" size="sm" class="font-medium">
                                    {{ $ticket->priority }}
                                </flux:badge>
                            </td>
                            <td class="px-4 py-3">
                                <flux:badge color="{{ $ticket->status === 'OPEN' ? 'green' : ($ticket->status === 'CLOSED' ? 'zinc' : 'yellow') }}" size="sm" class="font-medium">
                                    {{ str_replace('_', ' ', $ticket->status) }}
                                </flux:badge>
                            </td>
                            <td class="px-4 py-3 text-xs text-zinc-400 dark:text-zinc-500">{{ $ticket->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-10 text-center text-sm text-zinc-400 dark:text-zinc-500">{{ __('Belum ada ticket') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-zinc-100 dark:border-zinc-800">
            {{ $tickets->links() }}
        </div>
    </flux:card>
</div>
