<div>
    <div class="flex items-start gap-4 mb-6 flex-wrap">
        <div class="flex-1 min-w-0" wire:poll.15s="checkNewComments">
            <flux:heading size="xl" level="1" class="dark:text-white">{{ $ticket->ticket_number }}</flux:heading>
            <flux:subheading class="dark:text-zinc-400">{{ $ticket->title }}</flux:subheading>
        </div>

        <div class="flex gap-2 flex-shrink-0">
            @can('close', $ticket)
                <flux:dropdown>
                    <flux:button icon="chevron-down">{{ __('Ubah Status') }}</flux:button>
                    <flux:menu>
                        @if (in_array($ticket->status, ['OPEN', 'ASSIGNED']))
                            <flux:menu.item wire:click="changeStatus('IN_PROGRESS')">In Progress</flux:menu.item>
                        @endif
                        @if ($ticket->status !== 'WAITING_USER')
                            <flux:menu.item wire:click="changeStatus('WAITING_USER')">Waiting User</flux:menu.item>
                        @endif
                        @if ($ticket->status === 'RESOLVED' || $ticket->status === 'IN_PROGRESS')
                            <flux:menu.item wire:click="changeStatus('RESOLVED')">Resolved</flux:menu.item>
                        @endif
                    </flux:menu>
                </flux:dropdown>
            @endcan

            @can('close', $ticket)
                <flux:button wire:click="changeStatus('CLOSED')" icon="check" color="green">{{ __('Tutup') }}</flux:button>
            @endcan

            @can('reopen', $ticket)
                <flux:button wire:click="changeStatus('REOPEN')" icon="arrow-uturn-left">{{ __('Buka Kembali') }}</flux:button>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <flux:card class="dark:bg-zinc-900 dark:border-zinc-700">
                <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
                    <flux:heading class="dark:text-white">{{ __('Detail Ticket') }}</flux:heading>
                </div>

                <div class="p-6 space-y-4">
                    <p class="whitespace-pre-wrap text-zinc-800 dark:text-zinc-200 leading-relaxed">{{ $ticket->description }}</p>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-zinc-500 dark:text-zinc-400">{{ __('Requester') }}:</span>
                            <span class="font-medium text-zinc-900 dark:text-white ml-1">{{ $ticket->requester?->name }}</span>
                        </div>
                        <div>
                            <span class="text-zinc-500 dark:text-zinc-400">{{ __('Departemen') }}:</span>
                            <span class="font-medium text-zinc-900 dark:text-white ml-1">{{ $ticket->department?->name ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-zinc-500 dark:text-zinc-400">{{ __('Kategori') }}:</span>
                            <span class="font-medium text-zinc-900 dark:text-white ml-1">{{ $ticket->category?->name ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-zinc-500 dark:text-zinc-400">{{ __('Dibuat') }}:</span>
                            <span class="font-medium text-zinc-900 dark:text-white ml-1">{{ $ticket->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-zinc-500 dark:text-zinc-400">{{ __('Prioritas') }}:</span>
                            <span class="ml-1">
                                <flux:badge color="{{ $ticket->priority === 'URGENT' ? 'red' : ($ticket->priority === 'HIGH' ? 'orange' : ($ticket->priority === 'MEDIUM' ? 'blue' : 'slate')) }}" size="sm">
                                    {{ $ticket->priority }}
                                </flux:badge>
                            </span>
                        </div>
                        <div>
                            <span class="text-zinc-500 dark:text-zinc-400">{{ __('Status') }}:</span>
                            <span class="ml-1">
                                <flux:badge color="{{ $ticket->status === 'OPEN' ? 'green' : ($ticket->status === 'CLOSED' ? 'slate' : 'yellow') }}" size="sm">
                                    {{ str_replace('_', ' ', $ticket->status) }}
                                </flux:badge>
                            </span>
                        </div>
                        @if ($currentAssignment)
                            <div>
                                <span class="text-zinc-500 dark:text-zinc-400">{{ __('Assigned To') }}:</span>
                                <span class="font-medium text-zinc-900 dark:text-white ml-1">{{ $currentAssignment->assignedTo->name }}</span>
                            </div>
                        @endif
                        @if ($ticket->closed_at)
                            <div>
                                <span class="text-zinc-500 dark:text-zinc-400">{{ __('Ditutup') }}:</span>
                                <span class="font-medium text-zinc-900 dark:text-white ml-1">{{ $ticket->closed_at->format('d M Y H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </flux:card>

            @can('assign', $ticket)
                <flux:card class="dark:bg-zinc-900 dark:border-zinc-700">
                    <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
                        <flux:heading class="dark:text-white">{{ __('Assign Ticket') }}</flux:heading>
                    </div>

                    <div class="p-6">
                        <form wire:submit="assign" class="flex items-end gap-4 flex-wrap">
                            <flux:field class="flex-1 min-w-60">
                                <flux:label class="dark:text-zinc-300">{{ __('Pilih Agent') }}</flux:label>
                                <flux:select wire:model="assignedUserId" class="dark:bg-zinc-800 dark:border-zinc-600 dark:text-white">
                                    <option value="">{{ __('Pilih agent...') }}</option>
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->id }}">{{ $agent->name }} ({{ $agent->department?->name ?? '-' }})</option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="assignedUserId" />
                            </flux:field>

                            <flux:button type="submit" variant="primary">{{ __('Assign') }}</flux:button>
                        </form>
                    </div>
                </flux:card>
            @endcan

            @if ($attachments->isNotEmpty())
                <flux:card class="dark:bg-zinc-900 dark:border-zinc-700">
                    <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
                        <flux:heading class="dark:text-white">{{ __('Lampiran') }} ({{ $attachments->count() }})</flux:heading>
                    </div>

                    <div class="p-6 space-y-2">
                        @foreach ($attachments as $file)
                            <div class="flex items-center gap-3 py-2 border-b border-zinc-100 dark:border-zinc-800 last:border-0">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-zinc-900 dark:text-white truncate">{{ $file->filename }}</p>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                        {{ number_format($file->size / 1024, 1) }} KB &middot; {{ $file->uploadedBy?->name }}
                                    </p>
                                </div>
                                <a href="{{ asset('storage/'.$file->path) }}" target="_blank"
                                   class="shrink-0 text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                    {{ __('Download') }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </flux:card>
            @endif

            @can('update', $ticket)
                <flux:card class="dark:bg-zinc-900 dark:border-zinc-700">
                    <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
                        <flux:heading class="dark:text-white">{{ __('Upload Lampiran') }}</flux:heading>
                    </div>

                    <div class="p-6">
                        <form wire:submit="uploadAttachment" class="space-y-3">
                            <flux:field>
                                <flux:label class="dark:text-zinc-300">{{ __('Pilih File') }}</flux:label>
                                <input type="file" wire:model="attachment"
                                       class="block w-full text-sm text-zinc-700 dark:text-zinc-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-zinc-100 file:text-zinc-700 dark:file:bg-zinc-800 dark:file:text-zinc-300 hover:file:bg-zinc-200 dark:hover:file:bg-zinc-700" />
                                <flux:error name="attachment" />
                            </flux:field>

                            <div wire:loading wire:target="attachment" class="text-sm text-zinc-500 dark:text-zinc-400">
                                {{ __('Mengupload...') }}
                            </div>

                            <flux:button type="submit" variant="primary" wire:loading.attr="disabled" wire:target="attachment">
                                {{ __('Upload') }}
                            </flux:button>
                        </form>
                    </div>
                </flux:card>
            @endcan

            @if ($hasNewComments)
                <div class="rounded-lg bg-blue-50 dark:bg-blue-950 border border-blue-200 dark:border-blue-800 p-4 text-sm text-blue-700 dark:text-blue-300">
                    {{ __('Ada komentar baru.') }}
                </div>
            @endif

            <flux:card class="dark:bg-zinc-900 dark:border-zinc-700">
                <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
                    <flux:heading class="dark:text-white">{{ __('Komentar') }}</flux:heading>
                </div>

                <div class="p-6 space-y-4">
                    @forelse ($comments as $comment)
                        <div class="border-b border-zinc-100 dark:border-zinc-700 pb-4 last:border-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-medium text-sm text-zinc-900 dark:text-zinc-100">{{ $comment->user->name }}</span>
                                <span class="text-xs text-zinc-500 dark:text-zinc-500">{{ $comment->created_at->diffForHumans() }}</span>
                                @if ($comment->is_internal)
                                    <flux:badge color="orange" size="sm">{{ __('Internal') }}</flux:badge>
                                @endif
                            </div>
                            <p class="text-sm text-zinc-700 dark:text-zinc-300 whitespace-pre-wrap leading-relaxed">{{ $comment->comment }}</p>
                        </div>
                    @empty
                        <p class="text-zinc-500 dark:text-zinc-400 text-sm">{{ __('Belum ada komentar') }}</p>
                    @endforelse

                    @can('comment', $ticket)
                        <form wire:submit="addComment" class="space-y-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                            @can('commentInternal', $ticket)
                                <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                                    <input type="checkbox" wire:model="isInternal" class="rounded border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800" />
                                    {{ __('Komentar Internal') }}
                                </label>
                            @endcan

                            <flux:textarea wire:model="comment" rows="3" placeholder="{{ __('Tulis komentar...') }}" class="dark:bg-zinc-800 dark:border-zinc-600 dark:text-white dark:placeholder-zinc-500" />
                            <flux:error name="comment" />

                            <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                                {{ __('Kirim Komentar') }}
                            </flux:button>
                        </form>
                    @endcan
                </div>
            </flux:card>
        </div>

        <div class="space-y-6">
            <flux:card class="dark:bg-zinc-900 dark:border-zinc-700">
                <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
                    <flux:heading class="dark:text-white">{{ __('Riwayat') }}</flux:heading>
                </div>

                <div class="p-6 space-y-3">
                    @forelse ($histories as $history)
                        <div class="text-sm border-l-2 border-zinc-300 dark:border-zinc-600 pl-3">
                            <div class="text-xs text-zinc-500 dark:text-zinc-500">{{ $history->created_at->format('d M H:i') }}</div>
                            <div class="text-zinc-900 dark:text-zinc-100">{{ $history->performedBy?->name ?? '-' }}</div>
                            <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                {{ ucfirst(str_replace('_', ' ', $history->action)) }}
                                @if ($history->old_value && $history->new_value)
                                    : <span class="text-zinc-700 dark:text-zinc-300">{{ $history->old_value }}</span> → <span class="text-zinc-700 dark:text-zinc-300">{{ $history->new_value }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-zinc-500 dark:text-zinc-400 text-sm">{{ __('Belum ada riwayat') }}</p>
                    @endforelse
                </div>
            </flux:card>
        </div>
    </div>
</div>
