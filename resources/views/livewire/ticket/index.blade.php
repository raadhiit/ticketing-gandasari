<div>
    <flux:heading size="xl" level="1">{{ __('Tickets') }}</flux:heading>

    <flux:subheading class="mb-6">{{ __('Kelola dan pantau semua ticket') }}</flux:subheading>

    <div class="grid grid-cols-4 gap-4 mb-6">
        <flux:card class="p-4">
            <div class="text-sm text-zinc-500">{{ __('Open') }}</div>
            <div class="text-2xl font-bold">{{ $counts['open'] }}</div>
        </flux:card>
        <flux:card class="p-4">
            <div class="text-sm text-zinc-500">{{ __('Assigned') }}</div>
            <div class="text-2xl font-bold">{{ $counts['assigned'] }}</div>
        </flux:card>
        <flux:card class="p-4">
            <div class="text-sm text-zinc-500">{{ __('In Progress') }}</div>
            <div class="text-2xl font-bold">{{ $counts['in_progress'] }}</div>
        </flux:card>
        <flux:card class="p-4">
            <div class="text-sm text-zinc-500">{{ __('Closed') }}</div>
            <div class="text-2xl font-bold">{{ $counts['closed'] }}</div>
        </flux:card>
    </div>

    <div class="flex items-center gap-4 mb-4" wire:poll.30s="checkNewTickets">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Cari ticket...') }}" class="max-w-sm" />

        <flux:select wire:model.live="statusFilter" class="max-w-40">
            <option value="">{{ __('Semua Status') }}</option>
            <option value="OPEN">Open</option>
            <option value="ASSIGNED">Assigned</option>
            <option value="IN_PROGRESS">In Progress</option>
            <option value="WAITING_USER">Waiting User</option>
            <option value="RESOLVED">Resolved</option>
            <option value="CLOSED">Closed</option>
        </flux:select>

        <flux:select wire:model.live="priorityFilter" class="max-w-40">
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
        <flux:callout variant="info" icon="info" class="mb-4">
            {{ __('Ada ticket baru. Halaman diperbarui.') }}
        </flux:callout>
    @endif

    <flux:table>
        <flux:columns>
            <flux:column sortable :sorted="$sortField === 'ticket_number'" :direction="$sortDirection === 'asc' ? 'asc' : 'desc'" wire:click="sortBy('ticket_number')">#</flux:column>
            <flux:column sortable :sorted="$sortField === 'title'" :direction="$sortDirection === 'asc' ? 'asc' : 'desc'" wire:click="sortBy('title')">{{ __('Judul') }}</flux:column>
            <flux:column>{{ __('Requester') }}</flux:column>
            <flux:column>{{ __('Departemen') }}</flux:column>
            <flux:column>{{ __('Kategori') }}</flux:column>
            <flux:column sortable :sorted="$sortField === 'priority'" :direction="$sortDirection === 'asc' ? 'asc' : 'desc'" wire:click="sortBy('priority')">{{ __('Prioritas') }}</flux:column>
            <flux:column sortable :sorted="$sortField === 'status'" :direction="$sortDirection === 'asc' ? 'asc' : 'desc'" wire:click="sortBy('status')">{{ __('Status') }}</flux:column>
            <flux:column sortable :sorted="$sortField === 'created_at'" :direction="$sortDirection === 'asc' ? 'asc' : 'desc'" wire:click="sortBy('created_at')">{{ __('Dibuat') }}</flux:column>
        </flux:columns>

        <flux:rows>
            @forelse ($tickets as $ticket)
                <flux:row :href="route('tickets.show', $ticket)" wire:navigate class="cursor-pointer">
                    <flux:cell class="font-mono text-xs">{{ $ticket->ticket_number }}</flux:cell>
                    <flux:cell class="max-w-xs truncate">{{ $ticket->title }}</flux:cell>
                    <flux:cell>{{ $ticket->requester?->name }}</flux:cell>
                    <flux:cell>{{ $ticket->department?->name }}</flux:cell>
                    <flux:cell>{{ $ticket->category?->name }}</flux:cell>
                    <flux:cell>
                        <flux:badge color="{{ $ticket->priority === 'URGENT' ? 'red' : ($ticket->priority === 'HIGH' ? 'orange' : ($ticket->priority === 'MEDIUM' ? 'blue' : 'slate')) }}" size="sm">
                            {{ $ticket->priority }}
                        </flux:badge>
                    </flux:cell>
                    <flux:cell>
                        <flux:badge color="{{ $ticket->status === 'OPEN' ? 'green' : ($ticket->status === 'CLOSED' ? 'slate' : 'yellow') }}" size="sm">
                            {{ str_replace('_', ' ', $ticket->status) }}
                        </flux:badge>
                    </flux:cell>
                    <flux:cell class="text-xs">{{ $ticket->created_at->diffForHumans() }}</flux:cell>
                </flux:row>
            @empty
                <flux:row>
                    <flux:cell colspan="8" class="text-center text-zinc-500 py-8">
                        {{ __('Belum ada ticket') }}
                    </flux:cell>
                </flux:row>
            @endforelse
        </flux:rows>
    </flux:table>

    <div class="mt-4">
        {{ $tickets->links() }}
    </div>
</div>
