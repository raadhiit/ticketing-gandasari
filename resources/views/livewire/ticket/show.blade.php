<div x-data="{ previewUrl: '', previewName: '' }" class="space-y-5">
    <x-confirm-dialog name="confirm-ticket-delete" />
    <x-confirm-dialog name="confirm-attachment-delete" />
    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0 flex-1" wire:poll.15s="checkNewComments">
            <div class="flex items-center gap-2">
                <flux:button :href="route('tickets.index')" wire:navigate icon="arrow-left" variant="ghost"
                    class="-ms-2 shrink-0">{{ __('Back') }}</flux:button>
                <h1 class="text-xl font-semibold tracking-tight text-zinc-900 dark:text-white">
                    {{ $ticket->ticket_number }}</h1>
                <flux:badge
                    color="{{ $ticket->status === 'OPEN' ? 'green' : ($ticket->status === 'CLOSED' ? 'zinc' : 'yellow') }}"
                    size="sm" class="font-medium">
                    {{ str_replace('_', ' ', $ticket->status) }}
                </flux:badge>
                <flux:badge
                    color="{{ $ticket->priority === 'URGENT' ? 'red' : ($ticket->priority === 'HIGH' ? 'orange' : ($ticket->priority === 'MEDIUM' ? 'blue' : 'slate')) }}"
                    size="sm" class="font-medium">
                    {{ $ticket->priority }}
                </flux:badge>
            </div>
            <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1 ms-10">{{ $ticket->title }}</p>
        </div>

        <div class="flex gap-2 shrink-0">
            @can('update', $ticket)
                <flux:button :href="route('tickets.edit', $ticket)" wire:navigate icon="pencil-square" variant="filled">
                    {{ __('Edit') }}</flux:button>
            @endcan
            @can('close', $ticket)
                <flux:dropdown>
                    <flux:button icon="chevron-down" variant="primary">
                        {{ __('Ubah Status') }}
                    </flux:button>

                    <flux:menu>
                        @if ($ticket->status === 'OPEN')
                            <flux:menu.item wire:click="changeStatus('IN_PROGRESS')">
                                {{ __('Mulai Dikerjakan') }}
                            </flux:menu.item>
                        @endif

                        @if ($ticket->status === 'IN_PROGRESS')
                            <flux:menu.item wire:click="changeStatus('RESOLVED')">
                                {{ __('Tandai Selesai') }}
                            </flux:menu.item>
                        @endif

                        @if ($ticket->status === 'RESOLVED')
                            <flux:menu.item wire:click="changeStatus('CLOSED')">
                                {{ __('Tutup Ticket') }}
                            </flux:menu.item>
                        @endif
                    </flux:menu>
                </flux:dropdown>
            @endcan
            @can('reopen', $ticket)
                <flux:button wire:click="changeStatus('REOPEN')" icon="arrow-uturn-left">{{ __('Buka Kembali') }}
                </flux:button>
            @endcan

            @can('delete', $ticket)
                <flux:button x-data="{}"
                    @click="
                        $dispatch('confirm-open', { name: 'confirm-ticket-delete', title: '{{ __('Hapus Ticket') }}', message: '{{ __('Yakin ingin menghapus ticket ini? Semua data terkait akan ikut terhapus.') }}', method: 'delete', variant: 'danger', confirmLabel: '{{ __('Ya') }}' });
                        $wire.dispatch('modal-show', { name: 'confirm-ticket-delete' });
                    "
                    icon="trash" variant="danger">{{ __('Hapus') }}</flux:button>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-5">
            {{-- Detail Ticket --}}
            <flux:card
                class="p-5 border-l-[3px] border-l-zinc-400/50 dark:border-l-zinc-500/50 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-white mb-4">{{ __('Detail Ticket') }}</h2>
                <p class="whitespace-pre-wrap text-sm text-zinc-700 dark:text-zinc-300 leading-relaxed mb-4">
                    {{ $ticket->description }}</p>
                <div class="grid grid-cols-2 gap-x-6 gap-y-2 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="text-zinc-500 dark:text-zinc-400">{{ __('Requester') }}:</span>
                        <span
                            class="font-medium text-zinc-900 dark:text-white">{{ $ticket->requester_name ?: $ticket->requester?->name }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-zinc-500 dark:text-zinc-400">{{ __('Departemen') }}:</span>
                        <span
                            class="font-medium text-zinc-900 dark:text-white">{{ $ticket->department?->name ?? '-' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-zinc-500 dark:text-zinc-400">{{ __('Kategori') }}:</span>
                        <span
                            class="font-medium text-zinc-900 dark:text-white">{{ $ticket->category?->name ?? '-' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-zinc-500 dark:text-zinc-400">{{ __('Dibuat') }}:</span>
                        <span
                            class="font-medium text-zinc-900 dark:text-white">{{ $ticket->created_at->format('d M Y H:i') }}</span>
                    </div>
                    @if ($currentAssignment)
                        <div class="flex items-center gap-2">
                            <span class="text-zinc-500 dark:text-zinc-400">{{ __('Assigned To') }}:</span>
                            <span
                                class="font-medium text-zinc-900 dark:text-white">{{ $currentAssignment->assignedTo->name }}</span>
                        </div>
                    @endif
                    @if ($ticket->closed_at)
                        <div class="flex items-center gap-2">
                            <span class="text-zinc-500 dark:text-zinc-400">{{ __('Ditutup') }}:</span>
                            <span
                                class="font-medium text-zinc-900 dark:text-white">{{ $ticket->closed_at->format('d M Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            </flux:card>

            {{-- Assign --}}
            @can('assign', $ticket)
                <flux:card
                    class="p-5 border-l-[3px] border-l-blue-500 dark:border-l-blue-400 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
                    <h2 class="text-sm font-semibold text-zinc-900 dark:text-white mb-4">{{ __('Assign Ticket') }}</h2>
                    <form wire:submit="assign" class="flex items-end gap-3">
                        <flux:field class="flex-1">
                            <flux:select wire:model="assignedUserId"
                                class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
                                <option value="">{{ __('Pilih agent...') }}</option>
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->name }}
                                        ({{ $agent->department?->name ?? '-' }})</option>
                                @endforeach
                            </flux:select>
                            <flux:error name="assignedUserId" />
                        </flux:field>
                        <flux:button type="submit" variant="primary">{{ __('Assign') }}</flux:button>
                    </form>
                </flux:card>
            @endcan

            {{-- Attachments --}}
            @if ($attachments->isNotEmpty())
                <flux:card
                    class="p-5 border-l-[3px] border-l-purple-500 dark:border-l-purple-400 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
                    <h2 class="text-sm font-semibold text-zinc-900 dark:text-white mb-4">{{ __('Lampiran') }}
                        ({{ $attachments->count() }})</h2>
                    @php $previewMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml', 'image/bmp']; @endphp
                    <div class="space-y-2">
                        @foreach ($attachments as $file)
                            <div
                                class="flex items-center gap-3 py-2 border-b border-zinc-100 dark:border-zinc-800 last:border-0">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-zinc-900 dark:text-white truncate">
                                        {{ $file->filename }}</p>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                        {{ number_format($file->size / 1024, 1) }} KB &middot;
                                        {{ $file->uploadedBy?->name }}
                                    </p>
                                </div>
                                @if ($isPreviewable = in_array($file->mime_type, $previewMimes))
                                    <button type="button" wire:click="previewAttachment({{ $file->id }})"
                                        class="shrink-0 text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                        {{ __('Preview') }}
                                    </button>
                                @endif
                                <a href="{{ asset('storage/' . $file->path) }}" target="_blank"
                                    class="shrink-0 text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                    {{ __('Download') }}
                                </a>
                                @can('update', $ticket)
                                    <button type="button" x-data="{}"
                                        @click="
                                            $dispatch('confirm-open', { name: 'confirm-attachment-delete', title: '{{ __('Hapus Lampiran') }}', message: '{{ __('Yakin ingin menghapus lampiran ini?') }}', method: 'deleteAttachment', param: {{ $file->id }}, variant: 'danger', confirmLabel: '{{ __('Ya') }}' });
                                            $wire.dispatch('modal-show', { name: 'confirm-attachment-delete' });
                                        "
                                        class="shrink-0 text-sm text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                        {{ __('Hapus') }}
                                    </button>
                                @endcan
                            </div>
                        @endforeach
                    </div>
                </flux:card>
            @endif

            {{-- Upload Attachment --}}
            @can('update', $ticket)
                <flux:card
                    class="p-5 border-l-[3px] border-l-cyan-500 dark:border-l-cyan-400 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
                    <h2 class="text-sm font-semibold text-zinc-900 dark:text-white mb-4">{{ __('Upload Lampiran') }}</h2>
                    <form wire:submit="uploadAttachment" class="space-y-3">
                        <input type="file" wire:model="attachment"
                            class="block w-full text-sm text-zinc-600 dark:text-zinc-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-zinc-100 file:text-zinc-700 dark:file:bg-zinc-800 dark:file:text-zinc-300 hover:file:bg-zinc-200 dark:hover:file:bg-zinc-700" />
                        <flux:error name="attachment" />
                        <div wire:loading wire:target="attachment" class="text-sm text-zinc-500 dark:text-zinc-400">
                            {{ __('Mengupload...') }}</div>
                        <flux:button type="submit" variant="primary" wire:loading.attr="disabled" wire:target="attachment">
                            {{ __('Upload') }}
                        </flux:button>
                    </form>
                </flux:card>
            @endcan

            {{-- New Comments Alert --}}
            @if ($hasNewComments)
                <div
                    class="rounded-lg bg-blue-50 dark:bg-blue-950/50 border border-blue-200 dark:border-blue-800/50 px-4 py-3 text-sm text-blue-700 dark:text-blue-300">
                    {{ __('Ada komentar baru.') }}
                </div>
            @endif

            {{-- Komentar --}}
            <flux:card
                class="p-5 border-l-[3px] border-l-emerald-500 dark:border-l-emerald-400 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-white mb-4">{{ __('Komentar') }}</h2>
                <div class="space-y-4">
                    @forelse ($comments as $comment)
                        <div class="border-b border-zinc-100 dark:border-zinc-800 pb-4 last:border-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span
                                    class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $comment->user->name }}</span>
                                <span
                                    class="text-xs text-zinc-500 dark:text-zinc-500">{{ $comment->created_at->diffForHumans() }}</span>
                                @if ($comment->is_internal)
                                    <flux:badge color="orange" size="sm" class="font-medium">
                                        {{ __('Internal') }}</flux:badge>
                                @endif
                            </div>
                            <p class="text-sm text-zinc-700 dark:text-zinc-300 whitespace-pre-wrap leading-relaxed">
                                {{ $comment->comment }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Belum ada komentar') }}</p>
                    @endforelse

                    @can('comment', $ticket)
                        <form wire:submit="addComment"
                            class="space-y-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                            @can('commentInternal', $ticket)
                                <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                                    <input type="checkbox" wire:model="isInternal"
                                        class="rounded border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800" />
                                    {{ __('Komentar Internal') }}
                                </label>
                            @endcan
                            <flux:textarea wire:model="comment" rows="3"
                                placeholder="{{ __('Tulis komentar...') }}"
                                class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:placeholder-zinc-500" />
                            <flux:error name="comment" />
                            <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                                {{ __('Kirim Komentar') }}
                            </flux:button>
                        </form>
                    @endcan
                </div>
            </flux:card>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">
            {{-- Riwayat --}}
            <flux:card
                class="p-5 border-l-[3px] border-l-amber-500 dark:border-l-amber-400 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-white mb-4">{{ __('Riwayat') }}</h2>
                <div class="space-y-3">
                    @forelse ($histories as $history)
                        <div class="text-sm border-l-2 border-zinc-200 dark:border-zinc-700 pl-3">
                            <div class="text-xs text-zinc-500 dark:text-zinc-500">
                                {{ $history->created_at->format('d M H:i') }}</div>
                            <div class="font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $history->performedBy?->name ?? '-' }}</div>
                            <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                {{ ucfirst(str_replace('_', ' ', $history->action)) }}
                                @if ($history->old_value && $history->new_value)
                                    : <span class="text-zinc-700 dark:text-zinc-300">{{ $history->old_value }}</span>
                                    → <span class="text-zinc-700 dark:text-zinc-300">{{ $history->new_value }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Belum ada riwayat') }}</p>
                    @endforelse
                </div>
            </flux:card>
        </div>
    </div>

    {{-- Attachment Preview Modal --}}
    <flux:modal name="attachment-preview" class="max-w-4xl">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">{{ $previewName }}</h3>
            @if ($previewUrl)
                <img src="{{ $previewUrl }}" alt="{{ $previewName }}"
                    class="max-w-full max-h-[75vh] mx-auto rounded-lg">
            @endif
        </div>
    </flux:modal>
</div>
