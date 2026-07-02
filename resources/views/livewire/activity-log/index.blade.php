<div class="space-y-5">
    <div>
        <h1 class="text-xl font-semibold tracking-tight text-zinc-900 dark:text-white">{{ __('Activity Log') }}</h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">{{ __('Riwayat aktivitas pengguna') }}</p>
    </div>

    <div class="flex items-center gap-3 flex-wrap">
        <div class="relative flex-1 max-w-44">
            <flux:input wire:model.live="startDate" type="date" placeholder="{{ __('Dari Tanggal') }}" />
        </div>
        <div class="relative flex-1 max-w-44">
            <flux:input wire:model.live="endDate" type="date" placeholder="{{ __('Sampai Tanggal') }}" />
        </div>
        <flux:select wire:model.live="actionFilter" class="max-w-36">
            <option value="">{{ __('Semua Aksi') }}</option>
            <option value="created">Created</option>
            <option value="updated">Updated</option>
            <option value="deleted">Deleted</option>
        </flux:select>
        <flux:button wire:click="$refresh" icon="arrow-path" variant="ghost" class="shrink-0">{{ __('Refresh') }}</flux:button>
    </div>

    <flux:card class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-100 dark:divide-zinc-800">
                <thead>
                    <tr class="border-b border-zinc-100 dark:border-zinc-800">
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">{{ __('Waktu') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">{{ __('Pengguna') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">{{ __('Aksi') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">{{ __('Deskripsi') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">{{ __('Detail') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50 dark:divide-zinc-800/50">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-4 py-3 text-xs text-zinc-400 whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-zinc-700 dark:text-zinc-300">{{ $log->user?->name ?? '-' }}</div>
                                <div class="text-xs text-zinc-400">{{ $log->user?->email ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <flux:badge color="{{ $log->action === 'created' ? 'green' : ($log->action === 'updated' ? 'blue' : 'red') }}" size="sm">
                                    {{ ucfirst($log->action) }}
                                </flux:badge>
                            </td>
                            <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $log->description }}</td>
                            <td class="px-4 py-3 text-xs text-zinc-400">
                                @if ($log->subject_type)
                                    <span class="font-mono">{{ class_basename($log->subject_type) }}#{{ $log->subject_id }}</span>
                                @else
                                    <span class="text-zinc-300">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-zinc-400">{{ __('Belum ada aktivitas') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-zinc-100 dark:border-zinc-800">
            {{ $logs->links() }}
        </div>
    </flux:card>
</div>
