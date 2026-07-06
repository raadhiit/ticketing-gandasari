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

    $sidebarAccentBar = match ($ticket->status) {
        'OPEN' => 'bg-sky-400 dark:bg-sky-500',
        'IN_PROGRESS' => 'bg-amber-400 dark:bg-amber-500',
        'RESOLVED' => 'bg-emerald-400 dark:bg-emerald-500',
        'CLOSED' => 'bg-rose-400 dark:bg-rose-500',
        default => 'bg-zinc-400 dark:bg-zinc-500',
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
            <div class="mb-5 grid grid-cols-1 gap-4 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-start">
                <div class="min-w-0">
                    <h1
                        class="max-w-4xl break-words text-2xl font-bold tracking-wide text-zinc-950 dark:text-white sm:text-3xl">
                        #{{ str_replace('TKT-', '', $ticket->ticket_number) }} {{ $ticket->title }}
                    </h1>
                </div>

                <div class="flex flex-row flex-wrap items-center gap-2 lg:flex-nowrap lg:justify-end">
                    @can('update', $ticket)
                        <flux:button :href="route('tickets.edit', $ticket)" wire:navigate icon="pencil-square"
                            variant="filled" class="shrink-0">
                            {{ __('Edit Ticket') }}
                        </flux:button>
                    @endcan

                    @can('close', $ticket)
                        <flux:dropdown>
                            <flux:button icon="chevron-down" variant="primary" class="shrink-0">
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
                        <flux:button wire:click="changeStatus('REOPEN')" icon="arrow-uturn-left" variant="filled"
                            class="shrink-0">
                            {{ __('Buka Kembali') }}
                        </flux:button>
                    @endcan

                    @can('delete', $ticket)
                        <flux:button x-data
                            @click="
                                $dispatch('confirm-open', {
                                    name: 'confirm-ticket-delete',
                                    title: '{{ __('Hapus Ticket') }}',
                                    message: '{{ __('Yakin ingin menghapus ticket ini? Semua data terkait akan ikut terhapus.') }}',
                                    method: 'delete',
                                    variant: 'danger',
                                    confirmLabel: '{{ __('Ya, Hapus') }}'
                                });

                                $nextTick(() => $flux.modal('confirm-ticket-delete').show());
                            "
                            icon="trash" variant="danger" class="shrink-0">
                            {{ __('Hapus') }}
                        </flux:button>
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

                    {{-- <p class="whitespace-pre-line text-[15px] leading-7 text-zinc-900 dark:text-zinc-100">
                        {{ $ticket->description }}
                    </p> --}}
                    <div class="trix-content text-[15px] leading-7 text-zinc-900 dark:text-zinc-100">
                        {!! \App\Support\CleanHtml::clean($ticket->description) !!}
                    </div>
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

                            {{-- <p class="whitespace-pre-line text-[15px] leading-7 text-zinc-900 dark:text-zinc-100">
                                {{ $reply->comment }}
                            </p> --}}
                            <div class="trix-content text-[15px] leading-7 text-zinc-900 dark:text-zinc-100">
                                {!! \App\Support\CleanHtml::clean($reply->comment) !!}
                            </div>
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

                        <x-trix-editor id="ticket-reply-editor" wire:model="comment"
                            placeholder="{{ __('Tulis balasan untuk ticket ini...') }}" />

                        <flux:error name="comment" />

                        <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            @can('update', $ticket)
                                <form wire:submit="uploadAttachment" class="flex items-center gap-2">
                                    <input type="file" wire:model="attachment"
                                        class="block max-w-xs text-sm text-zinc-600 file:mr-3 file:rounded-lg file:border-0 file:bg-zinc-100 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-zinc-700 hover:file:bg-zinc-200 dark:text-zinc-400 dark:file:bg-zinc-800 dark:file:text-zinc-300" />
                                    <flux:button type="button" wire:click="uploadAttachment" variant="filled" size="sm"
                                        wire:loading.attr="disabled" wire:target="attachment">
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
            {{-- Ringkasan Ticket --}}
            <div
                class="overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="border-b border-zinc-100 px-5 py-5 dark:border-zinc-800">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">
                                {{ __('Ringkasan Ticket') }}
                            </h2>
                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                {{ __('Informasi utama ticket') }}
                            </p>
                        </div>

                        <div class="flex flex-wrap justify-end gap-2">
                            <span
                                class="inline-flex items-center rounded-xl px-3 py-1 text-xs font-bold ring-1 ring-inset {{ $statusNoticeClasses }}">
                                {{ str_replace('_', ' ', $ticket->status) }}
                            </span>

                            <span
                                class="inline-flex items-center rounded-xl px-3 py-1 text-xs font-bold ring-1 ring-inset {{ $priorityClasses }}">
                                {{ $ticket->priority }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 p-5">
                    <div class="flex items-start gap-3">
                        <div
                            class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-xl bg-zinc-50 text-zinc-500 ring-1 ring-inset ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-400 dark:ring-zinc-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 7.5a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                            </svg>
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                                {{ __('Ditangani oleh') }}
                            </p>
                            <p class="mt-1 truncate text-sm font-semibold text-zinc-900 dark:text-white">
                                {{ $currentAssignment?->assignedTo?->name ?? __('Belum ditugaskan') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div
                            class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-xl bg-zinc-50 text-zinc-500 ring-1 ring-inset ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-400 dark:ring-zinc-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                                {{ __('Pelapor') }}
                            </p>
                            <p class="mt-1 truncate text-sm font-semibold text-zinc-900 dark:text-white">
                                {{ $requesterName }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div
                            class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-xl bg-zinc-50 text-zinc-500 ring-1 ring-inset ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-400 dark:ring-zinc-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 21h16.5M4.5 3h15m-13.5 0v18m11.5-18v18M8.25 7.5h7.5m-7.5 4.5h7.5m-7.5 4.5h7.5" />
                            </svg>
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                                {{ __('Departemen') }}
                            </p>
                            <p class="mt-1 truncate text-sm font-semibold text-zinc-900 dark:text-white">
                                {{ $ticket->department?->name ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div
                            class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-xl bg-zinc-50 text-zinc-500 ring-1 ring-inset ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-400 dark:ring-zinc-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5.25 7.5 12 3.75 18.75 7.5 12 11.25 5.25 7.5Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 12 12 15.75 18.75 12" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5.25 16.5 12 20.25 18.75 16.5" />
                            </svg>
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                                {{ __('Kategori') }}
                            </p>
                            <p class="mt-1 text-sm font-semibold text-zinc-900 dark:text-white">
                                {{ $ticket->category?->name ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div
                            class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-xl bg-zinc-50 text-zinc-500 ring-1 ring-inset ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-400 dark:ring-zinc-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z" />
                            </svg>
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                                {{ __('Dibuat') }}
                            </p>
                            <p class="mt-1 text-sm font-semibold text-zinc-900 dark:text-white">
                                {{ $ticket->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-1 2xl:grid-cols-2">
                        <div
                            class="rounded-2xl border border-blue-100 bg-blue-50 px-4 py-3 dark:border-blue-500/20 dark:bg-blue-500/10">
                            <div class="flex items-center gap-2">
                                <div
                                    class="flex size-8 items-center justify-center rounded-xl bg-white text-blue-600 dark:bg-blue-500/10 dark:text-blue-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z" />
                                    </svg>
                                </div>

                                <div>
                                    <p class="text-xs font-semibold text-blue-700 dark:text-blue-300">
                                        {{ __('Umur Ticket') }}
                                    </p>
                                    <p class="mt-0.5 text-sm font-bold text-zinc-900 dark:text-white">
                                        {{ $ticket->created_at->diffForHumans(null, true) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="rounded-2xl border border-blue-100 bg-blue-50 px-4 py-3 dark:border-blue-500/20 dark:bg-blue-500/10">
                            <div class="flex items-center gap-2">
                                <div
                                    class="flex size-8 items-center justify-center rounded-xl bg-white text-blue-600 dark:bg-blue-500/10 dark:text-blue-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182" />
                                    </svg>
                                </div>

                                <div>
                                    <p class="text-xs font-semibold text-blue-700 dark:text-blue-300">
                                        {{ __('Update Terakhir') }}
                                    </p>
                                    <p class="mt-0.5 text-sm font-bold text-zinc-900 dark:text-white">
                                        {{ $ticket->updated_at->format('d M H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Penanggung Jawab --}}
            @can('assign', $ticket)
                <div id="assign-panel"
                    class="overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="border-b border-zinc-100 px-5 py-5 dark:border-zinc-800">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">
                            {{ __('Penanggung Jawab') }}
                        </h2>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                            {{ $currentAssignment ? __('Ticket saat ini sudah ditangani agent.') : __('Ticket belum ditugaskan ke agent.') }}
                        </p>
                    </div>

                    <div class="space-y-4 p-5">
                        <div @class([
                            'rounded-2xl border px-4 py-3',
                            'border-emerald-200 bg-emerald-50 dark:border-emerald-500/20 dark:bg-emerald-500/10' => $currentAssignment,
                            'border-amber-200 bg-amber-50 dark:border-amber-500/20 dark:bg-amber-500/10' => !$currentAssignment,
                        ])>
                            <div class="flex items-center gap-3">
                                <div @class([
                                    'flex size-9 shrink-0 items-center justify-center rounded-xl',
                                    'bg-white text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300' => $currentAssignment,
                                    'bg-white text-amber-600 dark:bg-amber-500/10 dark:text-amber-300' => !$currentAssignment,
                                ])>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 7.5a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <p @class([
                                        'text-xs font-semibold uppercase tracking-wide',
                                        'text-emerald-700 dark:text-emerald-300' => $currentAssignment,
                                        'text-amber-700 dark:text-amber-300' => !$currentAssignment,
                                    ])>
                                        {{ __('Agent Saat Ini') }}
                                    </p>
                                    <p class="mt-0.5 truncate text-sm font-bold text-zinc-900 dark:text-white">
                                        {{ $currentAssignment?->assignedTo?->name ?? __('Belum ditugaskan') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <form wire:submit="assign" class="space-y-3">
                            <flux:field>
                                <flux:select wire:model="assignedUserId"
                                    class="h-12 rounded-2xl dark:bg-zinc-950 dark:border-zinc-700/50 dark:text-white">
                                    <option value="">{{ __('Pilih agent...') }}</option>

                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->id }}">
                                            {{ $agent->name }} ({{ $agent->department?->name ?? '-' }})
                                        </option>
                                    @endforeach
                                </flux:select>

                                <flux:error name="assignedUserId" />
                            </flux:field>

                            <flux:button type="submit" variant="primary" class="w-full rounded-2xl">
                                {{ $currentAssignment ? __('Ubah Agent') : __('Tugaskan Ticket') }}
                            </flux:button>
                        </form>
                    </div>
                </div>
            @endcan

            {{-- Riwayat Ticket --}}
            <div
                class="overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div
                    class="flex items-start justify-between gap-3 border-b border-zinc-100 px-5 py-5 dark:border-zinc-800">
                    <div>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">
                            {{ __('Riwayat Ticket') }}
                        </h2>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                            {{ __('Aktivitas terbaru terkait ticket ini') }}
                        </p>
                    </div>

                    @if ($histories->isNotEmpty())
                        <span
                            class="shrink-0 rounded-xl bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-200 dark:bg-amber-500/10 dark:text-amber-300 dark:ring-amber-500/20">
                            {{ __('Update terbaru') }}
                        </span>
                    @endif
                </div>

                <div class="max-h-[460px] overflow-y-auto p-5">
                    <div class="relative space-y-5 border-l border-zinc-200 pl-5 dark:border-zinc-800">
                        @forelse ($histories as $history)
                            @php
                                $isLatest = $history->id === $latestHistoryId;

                                $actionLabel = match ($history->action) {
                                    'created' => 'Ticket dibuat',
                                    'updated' => 'Ticket diperbarui',
                                    'assigned' => 'Ticket ditugaskan',
                                    'status_changed' => 'Status diubah',
                                    'comment_added' => 'Balasan ditambahkan',
                                    'closed' => 'Ticket ditutup',
                                    'reopened' => 'Ticket dibuka kembali',
                                    default => ucfirst(str_replace('_', ' ', $history->action)),
                                };

                                $dotClass = match ($history->action) {
                                    'created' => 'bg-blue-500',
                                    'assigned' => 'bg-emerald-500',
                                    'status_changed' => 'bg-amber-500',
                                    'closed' => 'bg-rose-500',
                                    'reopened' => 'bg-sky-500',
                                    default => 'bg-zinc-400',
                                };
                            @endphp

                            <div class="relative">
                                <span @class([
                                    'absolute -left-[29px] top-1.5 flex size-4 rounded-full border-2 border-white dark:border-zinc-900',
                                    'bg-amber-500' => $isLatest,
                                    $dotClass => !$isLatest,
                                ])></span>

                                <div @class([
                                    'rounded-2xl border p-4 transition',
                                    'border-amber-200 bg-amber-50/80 shadow-sm dark:border-amber-500/20 dark:bg-amber-500/10' => $isLatest,
                                    'border-zinc-200 bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-950/50' => !$isLatest,
                                ])>
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <p @class([
                                                    'text-sm font-semibold',
                                                    'text-amber-950 dark:text-amber-100' => $isLatest,
                                                    'text-zinc-900 dark:text-white' => !$isLatest,
                                                ])>
                                                    {{ $history->performedBy?->name ?? __('System') }}
                                                </p>

                                                @if ($isLatest)
                                                    <span
                                                        class="rounded-xl bg-amber-100 px-2 py-0.5 text-[11px] font-bold uppercase tracking-wide text-amber-700 ring-1 ring-inset ring-amber-200 dark:bg-amber-500/20 dark:text-amber-200 dark:ring-amber-400/30">
                                                        {{ __('Terbaru') }}
                                                    </span>
                                                @endif
                                            </div>

                                            <p @class([
                                                'mt-1 text-sm',
                                                'text-amber-800 dark:text-amber-200/90' => $isLatest,
                                                'text-zinc-600 dark:text-zinc-400' => !$isLatest,
                                            ])>
                                                {{ $actionLabel }}

                                                @if ($history->old_value && $history->new_value)
                                                    :
                                                    <span class="font-semibold">
                                                        {{ $history->old_value }}
                                                    </span>
                                                    →
                                                    <span class="font-semibold">
                                                        {{ $history->new_value }}
                                                    </span>
                                                @endif
                                            </p>
                                        </div>

                                        <span @class([
                                            'shrink-0 text-xs',
                                            'text-amber-700/80 dark:text-amber-300/80' => $isLatest,
                                            'text-zinc-500 dark:text-zinc-500' => !$isLatest,
                                        ])>
                                            {{ $history->created_at->format('d M H:i') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div
                                class="rounded-2xl border border-dashed border-zinc-300 px-4 py-8 text-center dark:border-zinc-700">
                                <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                    {{ __('Belum ada riwayat') }}
                                </p>
                                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ __('Aktivitas ticket akan muncul di sini.') }}
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Attachment Preview Modal --}}
    <flux:modal name="attachment-preview" class="max-w-4xl">
        <div class="p-6">
            <h3 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-white">
                {{ $previewName }}
            </h3>

            @if ($previewUrl)
                <img src="{{ $previewUrl }}" alt="{{ $previewName }}"
                    class="mx-auto max-h-[75vh] max-w-full rounded-lg">
            @endif
        </div>
    </flux:modal>
</div>
