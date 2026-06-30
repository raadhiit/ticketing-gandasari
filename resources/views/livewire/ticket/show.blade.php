<div>
    <div class="flex items-start gap-4 mb-6" wire:poll.30s="checkNewComments">
        <div class="flex-1">
            <flux:heading size="xl" level="1">{{ $ticket->ticket_number }}</flux:heading>
            <flux:subheading>{{ $ticket->title }}</flux:subheading>
        </div>

        <div class="flex gap-2">
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

    <div class="grid grid-cols-3 gap-6">
        <div class="col-span-2 space-y-6">
            <flux:card>
                <flux:card.header>
                    <flux:heading>{{ __('Detail Ticket') }}</flux:heading>
                </flux:card.header>

                <flux:card.content class="space-y-4">
                    <p class="whitespace-pre-wrap">{{ $ticket->description }}</p>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-zinc-500">{{ __('Requester') }}:</span>
                            <span class="font-medium">{{ $ticket->requester?->name }}</span>
                        </div>
                        <div>
                            <span class="text-zinc-500">{{ __('Departemen') }}:</span>
                            <span class="font-medium">{{ $ticket->department?->name ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-zinc-500">{{ __('Kategori') }}:</span>
                            <span class="font-medium">{{ $ticket->category?->name ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-zinc-500">{{ __('Dibuat') }}:</span>
                            <span class="font-medium">{{ $ticket->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-zinc-500">{{ __('Prioritas') }}:</span>
                            <flux:badge color="{{ $ticket->priority === 'URGENT' ? 'red' : ($ticket->priority === 'HIGH' ? 'orange' : ($ticket->priority === 'MEDIUM' ? 'blue' : 'slate')) }}" size="sm">
                                {{ $ticket->priority }}
                            </flux:badge>
                        </div>
                        <div>
                            <span class="text-zinc-500">{{ __('Status') }}:</span>
                            <flux:badge color="{{ $ticket->status === 'OPEN' ? 'green' : ($ticket->status === 'CLOSED' ? 'slate' : 'yellow') }}" size="sm">
                                {{ str_replace('_', ' ', $ticket->status) }}
                            </flux:badge>
                        </div>
                        @if ($currentAssignment)
                            <div>
                                <span class="text-zinc-500">{{ __('Assigned To') }}:</span>
                                <span class="font-medium">{{ $currentAssignment->assignedTo->name }}</span>
                            </div>
                        @endif
                        @if ($ticket->closed_at)
                            <div>
                                <span class="text-zinc-500">{{ __('Ditutup') }}:</span>
                                <span class="font-medium">{{ $ticket->closed_at->format('d M Y H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </flux:card.content>
            </flux:card>

            @can('assign', $ticket)
                <flux:card>
                    <flux:card.header>
                        <flux:heading>{{ __('Assign Ticket') }}</flux:heading>
                    </flux:card.header>

                    <flux:card.content>
                        <form wire:submit="assign" class="flex items-end gap-4">
                            <flux:field class="flex-1">
                                <flux:label>{{ __('Pilih Agent') }}</flux:label>
                                <flux:select wire:model="assignedUserId">
                                    <option value="">{{ __('Pilih agent...') }}</option>
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->id }}">{{ $agent->name }} ({{ $agent->department?->name ?? '-' }})</option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="assignedUserId" />
                            </flux:field>

                            <flux:button type="submit" variant="primary">{{ __('Assign') }}</flux:button>
                        </form>
                    </flux:card.content>
                </flux:card>
            @endcan

            @if ($hasNewComments)
                <flux:callout variant="info" icon="info">
                    {{ __('Ada komentar baru.') }}
                </flux:callout>
            @endif

            <flux:card>
                <flux:card.header>
                    <flux:heading>{{ __('Komentar') }}</flux:heading>
                </flux:card.header>

                <flux:card.content class="space-y-4">
                    @forelse ($comments as $comment)
                        <div class="border-b border-zinc-200 pb-4 last:border-0 dark:border-zinc-700">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-medium text-sm">{{ $comment->user->name }}</span>
                                <span class="text-xs text-zinc-500">{{ $comment->created_at->diffForHumans() }}</span>
                                @if ($comment->is_internal)
                                    <flux:badge color="orange" size="sm">{{ __('Internal') }}</flux:badge>
                                @endif
                            </div>
                            <p class="text-sm whitespace-pre-wrap">{{ $comment->comment }}</p>
                        </div>
                    @empty
                        <p class="text-zinc-500 text-sm">{{ __('Belum ada komentar') }}</p>
                    @endforelse

                    @can('comment', $ticket)
                        <form wire:submit="addComment" class="space-y-3 pt-4">
                            @can('commentInternal', $ticket)
                                <flux:switch wire:model="isInternal" label="{{ __('Komentar Internal') }}" />
                            @endcan

                            <flux:textarea wire:model="comment" rows="3" placeholder="{{ __('Tulis komentar...') }}" />
                            <flux:error name="comment" />

                            <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                                {{ __('Kirim Komentar') }}
                            </flux:button>
                        </form>
                    @endcan
                </flux:card.content>
            </flux:card>
        </div>

        <div class="space-y-6">
            <flux:card>
                <flux:card.header>
                    <flux:heading>{{ __('Riwayat') }}</flux:heading>
                </flux:card.header>

                <flux:card.content class="space-y-3">
                    @forelse ($histories as $history)
                        <div class="text-sm border-l-2 border-zinc-300 pl-3 dark:border-zinc-600">
                            <div class="text-xs text-zinc-500">{{ $history->created_at->format('d M H:i') }}</div>
                            <div>{{ $history->performedBy?->name ?? '-' }}</div>
                            <div class="text-xs text-zinc-500">
                                {{ ucfirst(str_replace('_', ' ', $history->action)) }}
                                @if ($history->old_value && $history->new_value)
                                    : {{ $history->old_value }} → {{ $history->new_value }}
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-zinc-500 text-sm">{{ __('Belum ada riwayat') }}</p>
                    @endforelse
                </flux:card.content>
            </flux:card>
        </div>
    </div>
</div>
