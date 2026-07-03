{{-- Forum Header --}}
@php
    $requesterName = $ticket->requester_name ?: $ticket->requester?->name;
    $requesterInitial = strtoupper(substr($requesterName ?: 'U', 0, 1));

    $statusLabel = match ($ticket->status) {
        'OPEN' => 'Tiket Baru',
        'IN_PROGRESS' => 'Sedang Diproses',
        'RESOLVED' => 'Tiket ini sudah Selesai',
        'CLOSED' => 'Tiket ini sudah Ditutup',
        default => str_replace('_', ' ', $ticket->status),
    };

    $statusNoticeClasses = match ($ticket->status) {
        'OPEN' => 'border-sky-200 bg-sky-50 text-sky-900 dark:border-sky-800/60 dark:bg-sky-950/30 dark:text-sky-200',
        'IN_PROGRESS'
            => 'border-amber-200 bg-amber-50 text-amber-900 dark:border-amber-800/60 dark:bg-amber-950/30 dark:text-amber-200',
        'RESOLVED'
            => 'border-emerald-200 bg-emerald-50 text-emerald-900 dark:border-emerald-800/60 dark:bg-emerald-950/30 dark:text-emerald-200',
        'CLOSED'
            => 'border-rose-200 bg-rose-50 text-rose-900 dark:border-rose-800/60 dark:bg-rose-950/30 dark:text-rose-200',
        default => 'border-zinc-200 bg-zinc-50 text-zinc-900 dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-200',
    };

    $avatarClasses = match ($ticket->status) {
        'OPEN' => 'bg-sky-100 text-sky-700 ring-sky-200 dark:bg-sky-950/50 dark:text-sky-300 dark:ring-sky-800/70',
        'IN_PROGRESS'
            => 'bg-amber-100 text-amber-700 ring-amber-200 dark:bg-amber-950/50 dark:text-amber-300 dark:ring-amber-800/70',
        'RESOLVED'
            => 'bg-emerald-100 text-emerald-700 ring-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:ring-emerald-800/70',
        'CLOSED'
            => 'bg-rose-100 text-rose-700 ring-rose-200 dark:bg-rose-950/50 dark:text-rose-300 dark:ring-rose-800/70',
        default => 'bg-zinc-100 text-zinc-700 ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-700',
    };

    $priorityClasses = match ($ticket->priority) {
        'URGENT'
            => 'bg-rose-100 text-rose-700 ring-rose-200 dark:bg-rose-950/50 dark:text-rose-300 dark:ring-rose-800/70',
        'HIGH'
            => 'bg-orange-100 text-orange-700 ring-orange-200 dark:bg-orange-950/50 dark:text-orange-300 dark:ring-orange-800/70',
        'MEDIUM'
            => 'bg-blue-100 text-blue-700 ring-blue-200 dark:bg-blue-950/50 dark:text-blue-300 dark:ring-blue-800/70',
        'LOW' => 'bg-slate-100 text-slate-700 ring-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700',
        default => 'bg-zinc-100 text-zinc-700 ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-700',
    };
@endphp

<div wire:poll.15s="checkNewComments">
    <x-confirm-dialog name="confirm-ticket-delete" />
    <x-confirm-dialog name="confirm-attachment-delete" />
    {{-- Breadcrumb --}}
    <div class="mb-4 flex items-center gap-2 text-sm">
        <a href="{{ route('tickets.index') }}" wire:navigate
            class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400">
            {{ __('Daftar Ticket') }}
        </a>

        <span class="text-zinc-300 dark:text-zinc-700">/</span>

        <span
            class="rounded-full bg-zinc-100 px-2.5 py-1 font-mono text-xs font-semibold text-zinc-700 dark:bg-zinc-600 dark:text-zinc-300">
            {{ $ticket->ticket_number }}
        </span>
    </div>

    {{-- Status Notice --}}
    <div class="mb-4 rounded-lg border px-4 py-3 text-sm {{ $statusNoticeClasses }}">
        <span class="font-bold">{{ $statusLabel }}</span>
        <span class="mx-1">|</span>
        <span>{{ $ticket->updated_at->diffForHumans() }}</span>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        {{-- Left Thread --}}
        <div class="xl:col-span-2">
            {{-- Ticket Title + Actions --}}
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div class="min-w-0">
                    <h1 class="text-2xl font-bold tracking-wide text-zinc-950 dark:text-white sm:text-3xl">
                        #{{ str_replace('TKT-', '', $ticket->ticket_number) }} {{ $ticket->title }}
                    </h1>
                </div>

                <div
                    class="flex shrink-0 items-center rounded-lg border border-zinc-300 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    @can('update', $ticket)
                        <a href="{{ route('tickets.edit', $ticket) }}" wire:navigate
                            class="flex h-10 w-12 items-center justify-center border-r border-zinc-300 text-zinc-700 hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-800"
                            title="{{ __('Edit') }}">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 7.125 16.875 4.5" />
                            </svg>
                        </a>
                    @endcan

                    @can('close', $ticket)
                        <flux:dropdown>
                            <button type="button"
                                class="flex h-10 w-12 items-center justify-center border-r border-zinc-300 text-zinc-700 hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-800"
                                title="{{ __('Ubah Status') }}">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </button>

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
                        <button wire:click="changeStatus('REOPEN')" type="button"
                            class="flex h-10 w-12 items-center justify-center border-r border-zinc-300 text-zinc-700 hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-800"
                            title="{{ __('Buka Kembali') }}">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                            </svg>
                        </button>
                    @endcan

                    @can('delete', $ticket)
                        <button type="button" x-data
                            @click="
                                $dispatch('confirm-open', {
                                    name: 'confirm-ticket-delete',
                                    title: '{{ __('Hapus Ticket') }}',
                                    message: '{{ __('Yakin ingin menghapus ticket ini? Semua data terkait akan ikut terhapus.') }}',
                                    method: 'delete',
                                    variant: 'danger',
                                    confirmLabel: '{{ __('Ya') }}'
                                });
                                $nextTick(() => $flux.modal('confirm-ticket-delete').show());
                            "
                            class="flex h-10 w-12 items-center justify-center text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-950/30"
                            title="{{ __('Hapus') }}">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                    @endcan
                </div>
            </div>

            {{-- Main Ticket Post --}}
            <div class="relative mb-7 pl-16">
                <div
                    class="absolute left-0 top-0 flex h-14 w-14 items-center justify-center rounded-full text-lg font-semibold ring-1 {{ $avatarClasses }}">
                    {{ $requesterInitial }}
                </div>

                <div class="mb-3 flex flex-wrap items-center gap-x-2 gap-y-1">
                    <span class="font-bold text-zinc-950 dark:text-white">
                        {{ $requesterName }}
                    </span>

                    <span class="text-sm text-zinc-500 dark:text-zinc-400">
                        {{ __('dilaporkan') }} {{ $ticket->created_at->diffForHumans() }}
                    </span>
                </div>

                <div
                    class="relative rounded-lg border border-zinc-300 bg-white px-5 py-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <div
                        class="absolute -left-2 top-5 h-4 w-4 rotate-45 border-b border-l border-zinc-300 bg-white dark:border-zinc-700 dark:bg-zinc-900">
                    </div>

                    <p class="whitespace-pre-line text-[15px] leading-7 text-zinc-900 dark:text-zinc-100">
                        {{ $ticket->description }}
                    </p>
                </div>
            </div>

            {{-- Attachments --}}
            @if ($attachments->isNotEmpty())
                <div class="relative mb-7 pl-16">
                    <div
                        class="rounded-lg border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-800 dark:bg-zinc-900/70">
                        <p class="mb-3 text-sm font-bold text-zinc-900 dark:text-white">
                            {{ __('Lampiran') }} ({{ $attachments->count() }})
                        </p>

                        @php
                            $previewMimes = [
                                'image/jpeg',
                                'image/png',
                                'image/gif',
                                'image/webp',
                                'image/svg+xml',
                                'image/bmp',
                            ];
                        @endphp

                        <div class="space-y-2">
                            @foreach ($attachments as $file)
                                <div
                                    class="flex flex-col gap-2 rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-800 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="min-w-0">
                                        <p class="truncate font-semibold text-zinc-900 dark:text-white">
                                            {{ $file->filename }}
                                        </p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                            {{ number_format($file->size / 1024, 1) }} KB ·
                                            {{ $file->uploadedBy?->name }}
                                        </p>
                                    </div>

                                    <div class="flex shrink-0 items-center gap-3">
                                        @if (in_array($file->mime_type, $previewMimes))
                                            <button type="button" wire:click="previewAttachment({{ $file->id }})"
                                                class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                                {{ __('Preview') }}
                                            </button>
                                        @endif

                                        <a href="{{ asset('storage/' . $file->path) }}" target="_blank"
                                            class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                            {{ __('Download') }}
                                        </a>

                                        @can('update', $ticket)
                                            <button type="button" x-data="{}"
                                                @click="
                                                    $dispatch('confirm-open', {
                                                        name: 'confirm-attachment-delete',
                                                        title: '{{ __('Hapus Lampiran') }}',
                                                        message: '{{ __('Yakin ingin menghapus lampiran ini?') }}',
                                                        method: 'deleteAttachment',
                                                        param: {{ $file->id }},
                                                        variant: 'danger',
                                                        confirmLabel: '{{ __('Ya') }}'
                                                    });
                                                    $nextTick(() => $flux.modal('confirm-attachment-delete').show());
                                                "
                                                class="font-medium text-rose-600 hover:text-rose-700 dark:text-rose-400">
                                                {{ __('Hapus') }}
                                            </button>
                                        @endcan
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- New Reply Alert --}}
            @if ($hasNewComments)
                <div
                    class="mb-7 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700 dark:border-blue-800/50 dark:bg-blue-950/50 dark:text-blue-300">
                    {{ __('Ada balasan baru.') }}
                </div>
            @endif

            {{-- Replies --}}
            <div class="space-y-7">
                @forelse ($comments as $reply)
                    @php
                        $replyUserName = $reply->user->name;
                        $replyInitial = strtoupper(substr($replyUserName ?: 'U', 0, 1));
                    @endphp

                    <div class="relative pl-16">
                        <div
                            class="absolute left-0 top-0 flex h-14 w-14 items-center justify-center rounded-full bg-sky-100 text-lg font-semibold text-sky-700 ring-1 ring-sky-200 dark:bg-sky-950/50 dark:text-sky-300 dark:ring-sky-800/70">
                            {{ $replyInitial }}
                        </div>

                        <div class="mb-3 flex flex-wrap items-center gap-x-2 gap-y-1">
                            <span class="font-bold text-zinc-950 dark:text-white">
                                {{ $replyUserName }}
                            </span>

                            <span class="text-sm text-zinc-500 dark:text-zinc-400">
                                {{ __('berkata') }} {{ $reply->created_at->diffForHumans() }}
                            </span>

                            @if ($reply->is_internal)
                                <span
                                    class="rounded-full bg-orange-100 px-2 py-0.5 text-xs font-bold text-orange-700 ring-1 ring-orange-200 dark:bg-orange-950/50 dark:text-orange-300 dark:ring-orange-800/70">
                                    {{ __('Internal') }}
                                </span>
                            @endif
                        </div>

                        <div
                            class="relative rounded-lg border border-zinc-300 bg-white px-5 py-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                            <div
                                class="absolute -left-2 top-5 h-4 w-4 rotate-45 border-b border-l border-zinc-300 bg-white dark:border-zinc-700 dark:bg-zinc-900">
                            </div>

                            <p class="whitespace-pre-line text-[15px] leading-7 text-zinc-900 dark:text-zinc-100">
                                {{ $reply->comment }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="relative mb-7 pl-16">
                        <div
                            class="absolute left-0 top-0 flex h-14 w-14 items-center justify-center rounded-full bg-zinc-100 text-lg font-semibold text-zinc-500 ring-1 ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-400 dark:ring-zinc-700">
                            …
                        </div>

                        <div
                            class="rounded-lg border border-dashed border-zinc-300 bg-zinc-50 px-5 py-4 text-sm text-zinc-500 dark:border-zinc-700 dark:bg-zinc-900/60 dark:text-zinc-400">
                            {{ __('Belum ada balasan.') }}
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Reply Form --}}
            @can('comment', $ticket)
                @php
                    $currentUserName = auth()->user()?->name ?? 'User';
                    $currentInitial = strtoupper(substr($currentUserName, 0, 1));
                @endphp

                <div class="relative mt-7 pl-16">
                    <div
                        class="absolute left-0 top-0 flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 text-lg font-semibold text-blue-700 ring-1 ring-blue-200 dark:bg-blue-950/50 dark:text-blue-300 dark:ring-blue-800/70">
                        {{ $currentInitial }}
                    </div>

                    <div class="mb-3">
                        <span class="font-bold text-zinc-950 dark:text-white">
                            {{ $currentUserName }}
                        </span>
                    </div>

                    <form wire:submit="addComment"
                        class="relative rounded-lg border border-zinc-300 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                        <div
                            class="absolute -left-2 top-5 h-4 w-4 rotate-45 border-b border-l border-zinc-300 bg-white dark:border-zinc-700 dark:bg-zinc-900">
                        </div>

                        @can('commentInternal', $ticket)
                            <label class="mb-3 flex items-center gap-2 text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                <input type="checkbox" wire:model="isInternal"
                                    class="rounded border-zinc-300 bg-white dark:border-zinc-600 dark:bg-zinc-800" />
                                {{ __('Balasan Internal') }}
                            </label>
                        @endcan

                        <flux:textarea wire:model="comment" rows="6"
                            placeholder="{{ __('Tulis balasan untuk ticket ini...') }}"
                            class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:placeholder-zinc-500" />

                        <flux:error name="comment" />

                        <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            @can('update', $ticket)
                                <form wire:submit="uploadAttachment" class="flex items-center gap-2">
                                    <input type="file" wire:model="attachment"
                                        class="block max-w-xs text-sm text-zinc-600 file:mr-3 file:rounded-lg file:border-0 file:bg-zinc-100 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-zinc-700 hover:file:bg-zinc-200 dark:text-zinc-400 dark:file:bg-zinc-800 dark:file:text-zinc-300" />
                                    <flux:button type="button" wire:click="uploadAttachment" variant="filled"
                                        size="sm" wire:loading.attr="disabled" wire:target="attachment">
                                        {{ __('Upload') }}
                                    </flux:button>
                                </form>
                            @endcan

                            <div class="flex items-center gap-2">
                                <flux:button type="button" variant="filled" wire:click="$set('comment', '')">
                                    {{ __('Batal') }}
                                </flux:button>

                                <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                                    {{ __('Membalas') }}
                                </flux:button>
                            </div>
                        </div>
                    </form>
                </div>
            @endcan
        </div>

        {{-- Right Sidebar --}}
        <div class="space-y-5 xl:sticky xl:top-5 xl:self-start">
            {{-- Ticket Summary --}}
            <flux:card class="p-5 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
                <h2 class="mb-4 text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Detail Ticket') }}</h2>

                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                            {{ __('Status') }}</p>
                        <span
                            class="mt-1 inline-flex items-center rounded-full px-3 py-1 text-xs font-bold ring-1 ring-inset {{ $statusNoticeClasses }}">
                            {{ str_replace('_', ' ', $ticket->status) }}
                        </span>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                            {{ __('Prioritas') }}</p>
                        <span
                            class="mt-1 inline-flex items-center rounded-full px-3 py-1 text-xs font-bold ring-1 ring-inset {{ $priorityClasses }}">
                            {{ $ticket->priority }}
                        </span>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                            {{ __('Requester') }}</p>
                        <p class="mt-1 font-medium text-zinc-900 dark:text-white">{{ $requesterName }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                            {{ __('Departemen') }}</p>
                        <p class="mt-1 font-medium text-zinc-900 dark:text-white">
                            {{ $ticket->department?->name ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                            {{ __('Kategori') }}</p>
                        <p class="mt-1 font-medium text-zinc-900 dark:text-white">
                            {{ $ticket->category?->name ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                            {{ __('Dibuat') }}</p>
                        <p class="mt-1 font-medium text-zinc-900 dark:text-white">
                            {{ $ticket->created_at->format('d M Y H:i') }}</p>
                    </div>

                    @if ($currentAssignment)
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                                {{ __('Assigned To') }}</p>
                            <p class="mt-1 font-medium text-zinc-900 dark:text-white">
                                {{ $currentAssignment->assignedTo->name }}</p>
                        </div>
                    @endif
                </div>
            </flux:card>

            {{-- Assign --}}
            @can('assign', $ticket)
                <flux:card id="assign-panel" class="p-5 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
                    <h2 class="mb-4 text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Assign Ticket') }}</h2>

                    <form wire:submit="assign" class="space-y-3">
                        <flux:field>
                            <flux:select wire:model="assignedUserId"
                                class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
                                <option value="">{{ __('Pilih agent...') }}</option>
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}">
                                        {{ $agent->name }} ({{ $agent->department?->name ?? '-' }})
                                    </option>
                                @endforeach
                            </flux:select>
                            <flux:error name="assignedUserId" />
                        </flux:field>

                        <flux:button type="submit" variant="primary" class="w-full">
                            {{ __('Assign') }}
                        </flux:button>
                    </form>
                </flux:card>
            @endcan

            {{-- Riwayat --}}
            <flux:card
                class="p-5 border-l-[3px] border-l-amber-500 dark:border-l-amber-400 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-white mb-4">{{ __('Riwayat') }}</h2>

                <div class="space-y-3">
                    @forelse ($histories as $history)
                        <div class="text-sm border-l-2 border-zinc-200 dark:border-zinc-700 pl-3">
                            <div class="text-xs text-zinc-500 dark:text-zinc-500">
                                {{ $history->created_at->format('d M H:i') }}
                            </div>

                            <div class="font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $history->performedBy?->name ?? '-' }}
                            </div>

                            <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                {{ ucfirst(str_replace('_', ' ', $history->action)) }}

                                @if ($history->old_value && $history->new_value)
                                    :
                                    <span class="text-zinc-700 dark:text-zinc-300">{{ $history->old_value }}</span>
                                    →
                                    <span class="text-zinc-700 dark:text-zinc-300">{{ $history->new_value }}</span>
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
            <h3 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-white">
                {{ $previewName }}
            </h3>

            @if ($previewUrl)
                <img
                    src="{{ $previewUrl }}"
                    alt="{{ $previewName }}"
                    class="mx-auto max-h-[75vh] max-w-full rounded-lg"
                >
            @endif
        </div>
    </flux:modal>
</div>
