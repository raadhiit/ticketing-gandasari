<div>
    <flux:heading size="xl" level="1" class="dark:text-white">{{ __('Tickets') }}</flux:heading>

    <flux:subheading class="mb-6 dark:text-zinc-400">{{ __('Kelola dan pantau semua ticket') }}</flux:subheading>

    <div class="grid grid-cols-4 gap-4 mb-6">
        <flux:card class="p-4 dark:bg-zinc-900 dark:border-zinc-700">
            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Open') }}</div>
            <div class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $counts['open'] }}</div>
        </flux:card>
        <flux:card class="p-4 dark:bg-zinc-900 dark:border-zinc-700">
            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Assigned') }}</div>
            <div class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $counts['assigned'] }}</div>
        </flux:card>
        <flux:card class="p-4 dark:bg-zinc-900 dark:border-zinc-700">
            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('In Progress') }}</div>
            <div class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $counts['in_progress'] }}</div>
        </flux:card>
        <flux:card class="p-4 dark:bg-zinc-900 dark:border-zinc-700">
            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Closed') }}</div>
            <div class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $counts['closed'] }}</div>
        </flux:card>
    </div>

    <div class="flex items-center gap-4 mb-4 flex-wrap">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Cari ticket...') }}" class="max-w-sm dark:bg-zinc-900 dark:border-zinc-700 dark:text-white dark:placeholder-zinc-500" />

        <flux:select wire:model.live="statusFilter" class="max-w-40 dark:bg-zinc-900 dark:border-zinc-700 dark:text-white">
            <option value="">{{ __('Semua Status') }}</option>
            <option value="OPEN">Open</option>
            <option value="ASSIGNED">Assigned</option>
            <option value="IN_PROGRESS">In Progress</option>
            <option value="WAITING_USER">Waiting User</option>
            <option value="RESOLVED">Resolved</option>
            <option value="CLOSED">Closed</option>
        </flux:select>

        <flux:select wire:model.live="priorityFilter" class="max-w-40 dark:bg-zinc-900 dark:border-zinc-700 dark:text-white">
            <option value="">{{ __('Semua Prioritas') }}</option>
            <option value="LOW">Low</option>
            <option value="MEDIUM">Medium</option>
            <option value="HIGH">High</option>
            <option value="URGENT">Urgent</option>
        </flux:select>

        <flux:spacer />

        <flux:button :href="route('tickets.create')" wire:navigate icon="plus">
            {{ __('Buat Ticket') }}
        </flux:button>
    </div>

    @if ($hasNewTickets)
        <div class="rounded-lg bg-blue-50 dark:bg-blue-950 border border-blue-200 dark:border-blue-800 p-4 mb-4 text-sm text-blue-700 dark:text-blue-300">
            {{ __('Ada ticket baru. Halaman diperbarui.') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-900">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider cursor-pointer select-none" wire:click="sortBy('ticket_number')">
                        <span class="flex items-center gap-1"># @if ($sortField === 'ticket_number')<span class="text-xs text-zinc-900 dark:text-white">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>@endif</span>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider cursor-pointer select-none" wire:click="sortBy('title')">
                        <span class="flex items-center gap-1">{{ __('Judul') }} @if ($sortField === 'title')<span class="text-xs text-zinc-900 dark:text-white">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>@endif</span>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Requester') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Departemen') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Kategori') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider cursor-pointer select-none" wire:click="sortBy('priority')">
                        <span class="flex items-center gap-1">{{ __('Prioritas') }} @if ($sortField === 'priority')<span class="text-xs text-zinc-900 dark:text-white">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>@endif</span>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider cursor-pointer select-none" wire:click="sortBy('status')">
                        <span class="flex items-center gap-1">{{ __('Status') }} @if ($sortField === 'status')<span class="text-xs text-zinc-900 dark:text-white">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>@endif</span>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider cursor-pointer select-none" wire:click="sortBy('created_at')">
                        <span class="flex items-center gap-1">{{ __('Dibuat') }} @if ($sortField === 'created_at')<span class="text-xs text-zinc-900 dark:text-white">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>@endif</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($tickets as $ticket)
                    <tr onclick="window.location='{{ route('tickets.show', $ticket) }}'" class="cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                        <td class="px-4 py-3 font-mono text-xs text-zinc-600 dark:text-zinc-400">{{ $ticket->ticket_number }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-900 dark:text-zinc-100">{{ $ticket->title }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-700 dark:text-zinc-300">{{ $ticket->requester?->name }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-700 dark:text-zinc-300">{{ $ticket->department?->name }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-700 dark:text-zinc-300">{{ $ticket->category?->name }}</td>
                        <td class="px-4 py-3">
                            <flux:badge color="{{ $ticket->priority === 'URGENT' ? 'red' : ($ticket->priority === 'HIGH' ? 'orange' : ($ticket->priority === 'MEDIUM' ? 'blue' : 'slate')) }}" size="sm">
                                {{ $ticket->priority }}
                            </flux:badge>
                        </td>
                        <td class="px-4 py-3">
                            <flux:badge color="{{ $ticket->status === 'OPEN' ? 'green' : ($ticket->status === 'CLOSED' ? 'slate' : 'yellow') }}" size="sm">
                                {{ str_replace('_', ' ', $ticket->status) }}
                            </flux:badge>
                        </td>
                        <td class="px-4 py-3 text-xs text-zinc-500 dark:text-zinc-500">{{ $ticket->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-zinc-500 dark:text-zinc-400">{{ __('Belum ada ticket') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $tickets->links() }}
    </div>
</div>
