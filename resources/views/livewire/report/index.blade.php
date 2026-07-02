<div class="space-y-5">
    <x-confirm-dialog name="confirm-export" />
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-semibold tracking-tight text-zinc-900 dark:text-white">{{ __('Laporan') }}</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">{{ __('Rekap dan ekspor data ticket') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <flux:button wire:click="resetFilters" variant="ghost" icon="x-mark" size="sm">{{ __('Reset') }}
            </flux:button>
            <flux:button wire:click="confirmExport" wire:loading.attr="disabled" icon="arrow-down-tray"
                variant="primary" size="sm">{{ __('Export XLSX') }}</flux:button>
        </div>
    </div>

    {{-- Filters --}}
    <flux:card
        class="p-5 border-l-[3px] border-l-violet-500 dark:border-l-violet-400 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <flux:field>
                <flux:label class="dark:text-zinc-300">{{ __('Dari Tanggal') }}</flux:label>
                <flux:input wire:model.live="startDate" type="date"
                    class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white" />
            </flux:field>
            <flux:field>
                <flux:label class="dark:text-zinc-300">{{ __('Sampai Tanggal') }}</flux:label>
                <flux:input wire:model.live="endDate" type="date"
                    class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white" />
            </flux:field>
            <flux:field>
                <flux:label class="dark:text-zinc-300">{{ __('Status') }}</flux:label>
                <flux:select wire:model.live="status" class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
                    <option value="">{{ __('Semua Status') }}</option>
                    @foreach ($statuses as $s)
                        <option value="{{ $s->value }}">{{ str_replace('_', ' ', $s->value) }}</option>
                    @endforeach
                </flux:select>
            </flux:field>
            <flux:field>
                <flux:label class="dark:text-zinc-300">{{ __('Departemen') }}</flux:label>
                <flux:select wire:model.live="departmentId"
                    class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
                    <option value="">{{ __('Semua Departemen') }}</option>
                    @foreach ($departments as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </flux:select>
            </flux:field>
            <flux:field>
                <flux:label class="dark:text-zinc-300">{{ __('Prioritas') }}</flux:label>
                <flux:select wire:model.live="priority" class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
                    <option value="">{{ __('Semua Prioritas') }}</option>
                    @foreach ($priorities as $p)
                        <option value="{{ $p->value }}">{{ $p->value }}</option>
                    @endforeach
                </flux:select>
            </flux:field>
        </div>
    </flux:card>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
        <flux:card class="p-4 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">{{ __('Total') }}
            </p>
            <p class="mt-1 text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['total'] }}</p>
        </flux:card>

        <flux:card class="p-4 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">{{ __('Open') }}
            </p>
            <p class="mt-1 text-2xl font-bold text-sky-600 dark:text-sky-400">{{ $stats['open'] }}</p>
        </flux:card>

        <flux:card class="p-4 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">
                {{ __('In Progress') }}</p>
            <p class="mt-1 text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['in_progress'] }}</p>
        </flux:card>

        <flux:card class="p-4 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">
                {{ __('Resolved') }}</p>
            <p class="mt-1 text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $stats['resolved'] }}</p>
        </flux:card>

        <flux:card class="p-4 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">{{ __('Closed') }}
            </p>
            <p class="mt-1 text-2xl font-bold text-rose-600 dark:text-rose-400">{{ $stats['closed'] }}</p>
        </flux:card>
    </div>

    {{-- Table --}}
    <flux:card
        class="overflow-hidden border-l-[3px] border-l-violet-500/50 dark:border-l-violet-400/50 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
        <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Data Ticket') }}</h2>
            <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ $tickets->count() }} tiket</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-100 dark:divide-zinc-800">
                <thead>
                    <tr class="border-b border-zinc-100 dark:border-zinc-800">
                        <th
                            class="px-5 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                            {{ __('Ticket') }}</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                            {{ __('Judul') }}</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                            {{ __('Requester') }}</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                            {{ __('Departemen') }}</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                            {{ __('Kategori') }}</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                            {{ __('Prioritas') }}</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                            {{ __('Status') }}</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                            {{ __('Assigned') }}</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                            {{ __('Dibuat') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50 dark:divide-zinc-800/50">
                    @forelse ($tickets as $ticket)
                        <tr onclick="window.location='{{ route('tickets.show', $ticket) }}'"
                            class="cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-5 py-3 font-mono text-xs text-zinc-500 dark:text-zinc-400">
                                {{ $ticket->ticket_number }}</td>
                            <td
                                class="px-5 py-3 text-sm font-medium text-zinc-900 dark:text-zinc-100 max-w-48 truncate">
                                {{ $ticket->title }}</td>
                            <td class="px-5 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                                {{ $ticket->requester_name ?: $ticket->requester?->name }}</td>
                            <td class="px-5 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                                {{ $ticket->department?->name ?? '-' }}</td>
                            <td class="px-5 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                                {{ $ticket->category?->name ?? '-' }}</td>
                            <td class="px-5 py-3">
                                <flux:badge
                                    color="{{ $ticket->priority === 'URGENT' ? 'red' : ($ticket->priority === 'HIGH' ? 'orange' : ($ticket->priority === 'MEDIUM' ? 'blue' : 'slate')) }}"
                                    size="sm" class="font-medium">
                                    {{ $ticket->priority }}
                                </flux:badge>
                            </td>
                            <td class="px-5 py-3">
                                <flux:badge
                                    color="{{ match ($ticket->status) {
                                        'OPEN' => 'sky',
                                        'IN_PROGRESS' => 'yellow',
                                        'RESOLVED' => 'green',
                                        'CLOSED' => 'red',
                                        default => 'zinc',
                                    } }}"
                                    size="sm" class="font-medium">
                                    {{ str_replace('_', ' ', $ticket->status) }}
                                </flux:badge>
                            </td>
                            <td class="px-5 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                                {{ $ticket->activeAssignment?->assignedTo?->name ?? '-' }}</td>
                            <td class="px-5 py-3 text-xs text-zinc-400 dark:text-zinc-500">
                                {{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-10 text-center text-sm text-zinc-400 dark:text-zinc-500">
                                {{ __('Tidak ada data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </flux:card>
</div>
