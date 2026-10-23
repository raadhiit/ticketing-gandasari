<div class="flex flex-col gap-3">
    <x-panel :title="__('Activity Log')">
        <div class="flex flex-wrap items-center gap-x-3.5 gap-y-2.5 border-b border-line px-4 py-3">
            <label for="al-start" class="sr-only">{{ __('Dari Tanggal') }}</label>
            <input id="al-start" type="date" wire:model.live="startDate" class="inp">

            <label for="al-end" class="sr-only">{{ __('Sampai Tanggal') }}</label>
            <input id="al-end" type="date" wire:model.live="endDate" class="inp">

            <label for="al-action" class="sr-only">{{ __('Aksi') }}</label>
            <select id="al-action" wire:model.live="actionFilter" class="sel">
                <option value="">{{ __('Semua Aksi') }}</option>
                <option value="created">Created</option>
                <option value="updated">Updated</option>
                <option value="deleted">Deleted</option>
            </select>

            <button type="button" wire:click="$refresh" class="cursor-pointer border-0 bg-transparent p-0 text-sm font-semibold text-link hover:text-link-hover">{{ __('Refresh') }}</button>
        </div>

        <div class="px-4 py-2 text-[13px] text-ink-label">{{ __('Riwayat aktivitas pengguna') }}</div>

        <div class="overflow-x-auto">
            <table class="tbl min-w-[760px]">
                <thead>
                    <tr>
                        <th>{{ __('Waktu') }}</th>
                        <th>{{ __('Pengguna') }}</th>
                        <th>{{ __('Aksi') }}</th>
                        <th>{{ __('Deskripsi') }}</th>
                        <th>{{ __('Detail') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($logs as $log)
                        <tr wire:key="log-{{ $log->id }}">
                            <td class="whitespace-nowrap text-muted">{{ $log->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <div>{{ $log->user?->name ?? '-' }}</div>
                                <div class="text-xs text-muted">{{ $log->user?->email }}</div>
                            </td>
                            <td>
                                <span @class([
                                    'pill',
                                    'pill-resolved' => $log->action === 'created',
                                    'pill-open' => $log->action === 'updated',
                                    'pill-closed' => ! in_array($log->action, ['created', 'updated'], true),
                                ])>{{ strtoupper($log->action) }}</span>
                            </td>
                            <td>{{ $log->description }}</td>
                            <td class="whitespace-nowrap text-muted">
                                @if ($log->subject_type)
                                    {{ class_basename($log->subject_type) }}#{{ $log->subject_id }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-muted">{{ __('Belum ada aktivitas') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $logs->links() }}
    </x-panel>
</div>
