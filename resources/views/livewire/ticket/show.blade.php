@php
    $user = auth()->user();
    $requesterName = $ticket->requester_name ?: $ticket->requester?->name;
    $agentName = $currentAssignment?->assignedTo?->name;
    $isClosed = $ticket->status === 'CLOSED';
    $isSupport = $user->can('ticket.assign');

    $prettify = fn (?string $value) => ucwords(strtolower(str_replace('_', ' ', (string) $value)));
    $latestHistory = $histories->first();

    $previewMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml', 'image/bmp'];

    $actionLabels = [
        'created' => 'Ticket dibuat',
        'updated' => 'Ticket diperbarui',
        'assigned' => 'Ticket ditugaskan',
        'status_changed' => 'Status diubah',
        'comment_added' => 'Balasan ditambahkan',
        'closed' => 'Ticket ditutup',
        'reopened' => 'Ticket dibuka kembali',
    ];
@endphp

<div wire:poll.15s="checkNewComments">
<div class="flex flex-wrap items-start gap-4">
    {{-- Left column --}}
    <div class="flex min-w-0 flex-[999_1_560px] flex-col gap-3">
        {{-- Toolbar --}}
        <div class="tbar print:hidden" role="toolbar" aria-label="{{ __('Aksi ticket') }}">
            @can('close', $ticket)
                <flux:dropdown>
                    <button type="button" class="tbar-btn">{{ __('Ubah Status') }}</button>

                    <flux:menu>
                        @if ($ticket->status === 'OPEN')
                            <flux:menu.item wire:click="changeStatus('IN_PROGRESS')">{{ __('Mulai Dikerjakan') }}</flux:menu.item>
                        @endif

                        @if ($ticket->status === 'IN_PROGRESS')
                            <flux:menu.item wire:click="changeStatus('RESOLVED')">{{ __('Tandai Selesai') }}</flux:menu.item>
                        @endif

                        @if ($ticket->status === 'RESOLVED')
                            <flux:menu.item wire:click="changeStatus('CLOSED')">{{ __('Tutup Ticket') }}</flux:menu.item>
                        @endif
                    </flux:menu>
                </flux:dropdown>
            @endcan

            @can('assign', $ticket)
                <flux:modal.trigger name="assign-agent">
                    <button type="button" class="tbar-btn">{{ __('Ubah Agent') }}</button>
                </flux:modal.trigger>
            @endcan

            @can('reopen', $ticket)
                <button type="button" class="tbar-btn" wire:click="changeStatus('REOPEN')">{{ __('Buka Kembali') }}</button>
            @endcan

            <button type="button" class="tbar-btn" onclick="window.print()">{{ __('Print') }}</button>

            @can('update', $ticket)
                <a href="{{ route('tickets.edit', $ticket) }}" wire:navigate class="tbar-btn">{{ __('Edit') }}</a>
            @endcan

            @can('ticket.assign')
                <a href="{{ route('tickets.create') }}" wire:navigate class="tbar-btn">{{ __('Buat Ticket') }}</a>
            @endcan

            @can('delete', $ticket)
                <button type="button" x-data class="tbar-btn is-danger"
                    @click="
                        $dispatch('confirm-open', {
                            name: 'confirm-ticket-delete',
                            title: '{{ __('Hapus Ticket') }}',
                            message: '{{ __('Yakin ingin menghapus ticket ini? Ticket tidak akan tampil lagi di daftar.') }}',
                            method: 'delete',
                            variant: 'danger',
                            confirmLabel: '{{ __('Ya, Hapus') }}'
                        });

                        $nextTick(() => $flux.modal('confirm-ticket-delete').show());
                    ">{{ __('Hapus') }}</button>
            @endcan
        </div>

        {{-- Status notice --}}
        @if ($isClosed)
            <div class="notice is-closed">
                <div class="font-bold">{{ __('Ticket Closed') }}</div>
                <div class="mt-0.5">
                    {{ __('Ticket ini sudah ditutup.') }}
                    @unless ($isSupport)
                        {{ __('Jika masalah belum selesai, silakan') }}
                        <a href="{{ route('tickets.create') }}" wire:navigate>{{ __('buat ticket baru') }}</a>.
                    @endunless
                </div>
            </div>
        @else
            <div class="notice">
                <div class="font-bold">{{ __('Ticket :status', ['status' => $prettify($ticket->status)]) }}</div>
                <div class="mt-0.5">
                    @if ($ticket->status === 'IN_PROGRESS')
                        {{ __('Ticket sedang dikerjakan oleh :agent.', ['agent' => $agentName ?? __('agent')]) }}
                    @elseif ($ticket->status === 'RESOLVED')
                        {{ __('Ticket sudah diselesaikan dan menunggu penutupan.') }}
                    @elseif ($latestHistory?->action === 'reopened')
                        {{ __('Ticket dibuka kembali oleh :name pada :date dan menunggu penanganan agent.', ['name' => $latestHistory->performedBy?->name ?? __('System'), 'date' => $latestHistory->created_at->format('d M Y H:i')]) }}
                    @elseif ($agentName)
                        {{ __('Ticket sudah ditugaskan ke :agent dan menunggu penanganan.', ['agent' => $agentName]) }}
                    @else
                        {{ __('Ticket sudah diterima dan menunggu ditugaskan ke agent.') }}
                        @if ($user->can('delete', $ticket) && ! $isSupport)
                            {{ __('Selama belum ditangani, ticket ini masih bisa Anda hapus.') }}
                        @endif
                    @endif
                </div>
            </div>
        @endif

        {{-- Header --}}
        <x-panel :title="__('Header Ticket')">
            <div class="kv">
                <div class="kv-k kv-first">{{ __('Judul') }}</div>
                <div class="kv-v kv-first kv-span5"><b>{{ $ticket->title }}</b></div>

                <div class="kv-k">{{ __('No. Ticket') }}</div>
                <div class="kv-v"><i>{{ $ticket->ticket_number }}</i></div>
                <div class="kv-k">{{ __('Status') }}</div>
                <div class="kv-v"><x-status-pill :status="$ticket->status" /></div>
                <div class="kv-k">{{ __('Prioritas') }}</div>
                <div class="kv-v"><x-priority-dot :priority="$ticket->priority" /></div>

                <div class="kv-k">{{ __('Pelapor') }}</div>
                <div class="kv-v">{{ $requesterName }}</div>
                <div class="kv-k">{{ __('Departemen') }}</div>
                <div class="kv-v">
                    @if ($ticket->department)
                        <a href="{{ route('tickets.index', ['departmentFilter' => $ticket->department_id]) }}" wire:navigate class="no-underline">{{ $ticket->department->name }}</a>
                    @else
                        -
                    @endif
                </div>
                <div class="kv-k">{{ __('Kategori') }}</div>
                <div class="kv-v">
                    @if ($ticket->category)
                        <a href="{{ route('tickets.index', ['categoryFilter' => $ticket->category_id]) }}" wire:navigate class="no-underline">{{ $ticket->category->name }}</a>
                    @else
                        -
                    @endif
                </div>

                <div class="kv-k">{{ __('Ditangani oleh') }}</div>
                <div class="kv-v">
                    @if ($agentName)
                        {{ $agentName }}
                    @else
                        <span class="text-muted">{{ __('Belum ditugaskan') }}</span>
                    @endif
                </div>
                <div class="kv-k">{{ __('Dibuat') }}</div>
                <div class="kv-v">{{ $ticket->created_at->format('d M Y H:i') }} · {{ $ticket->created_at->diffForHumans(null, true) }}</div>
                <div class="kv-k">{{ __('Update terakhir') }}</div>
                <div class="kv-v">{{ $ticket->updated_at->format('d M Y H:i') }}</div>
            </div>
        </x-panel>

        {{-- Conversation --}}
        <x-panel :title="__('Percakapan (:count)', ['count' => $comments->count() + 1])">
            <div class="thread">
                @if ($hasNewComments)
                    <div class="notice">{{ __('Ada balasan baru.') }}</div>
                @endif

                <x-thread-message :name="$requesterName" color="#2f62c9" role="reporter"
                    :when="__('dilaporkan').' '.$ticket->created_at->diffForHumans()">
                    <div class="bubble">
                        <div class="trix-content">{!! \App\Support\CleanHtml::clean($ticket->description) !!}</div>

                        @foreach ($attachments as $file)
                            <div class="mt-3">
                                @if (in_array($file->mime_type, $previewMimes))
                                    <button type="button" wire:click="previewAttachment({{ $file->id }})"
                                        class="mb-2.5 flex h-[200px] w-full cursor-zoom-in items-center justify-center overflow-hidden rounded-md border border-dashed border-[#aeb8c8] bg-kv p-0"
                                        aria-label="{{ __('Preview') }} {{ $file->filename }}">
                                        <img src="{{ asset('storage/'.$file->path) }}" alt="{{ $file->filename }}" class="max-h-full max-w-full object-contain">
                                    </button>
                                @endif

                                <div class="flex flex-wrap items-center gap-x-3 text-sm">
                                    <a href="{{ asset('storage/'.$file->path) }}" target="_blank">{{ $file->filename }} {{ number_format($file->size / 1024, 2) }} KB</a>

                                    @can('update', $ticket)
                                        <button type="button" x-data class="cursor-pointer border-0 bg-transparent p-0 text-[13px] font-semibold text-danger hover:text-danger-hover"
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
                                            ">{{ __('Hapus') }}</button>
                                    @endcan
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-thread-message>

                @forelse ($comments as $reply)
                    @php $fromReporter = $reply->user_id === $ticket->requester_id; @endphp

                    <x-thread-message :name="$reply->user->name" :color="$fromReporter ? '#2f62c9' : '#475569'"
                        :role="$fromReporter ? 'reporter' : 'agent'" :internal="$reply->is_internal"
                        :when="($reply->is_internal ? __('catatan internal').' · ' : __('berkata').' ').$reply->created_at->diffForHumans()">
                        <div @class(['bubble', 'is-internal' => $reply->is_internal])>
                            <div class="trix-content">{!! \App\Support\CleanHtml::clean($reply->comment) !!}</div>
                        </div>
                    </x-thread-message>
                @empty
                    <div class="empty-note">{{ __('Belum ada balasan.') }}</div>
                @endforelse

                @if ($isClosed)
                    <div class="empty-note">{{ __('Ticket sudah ditutup, balasan baru tidak dapat ditambahkan.') }}</div>
                @elseif ($user->can('comment', $ticket))
                    <x-thread-message :name="$user->name" color="#c2610c"
                        :when="$comments->isEmpty() ? __('tambah informasi') : __('balas ticket ini')">
                        <form wire:submit="addComment" class="bubble print:hidden">
                            @can('commentInternal', $ticket)
                                <label class="flex items-center gap-2 text-sm font-medium text-ink-label">
                                    <input type="checkbox" wire:model="isInternal"> {{ __('Balasan Internal') }}
                                </label>
                            @endcan

                            <x-trix-editor id="ticket-reply-editor" wire:model="comment"
                                placeholder="{{ __('Tulis balasan untuk ticket ini...') }}" />

                            @error('comment')
                                <p class="mt-2 mb-0 text-[13px] text-danger">{{ $message }}</p>
                            @enderror

                            @can('update', $ticket)
                                <div class="mt-3.5 flex flex-wrap items-center gap-2.5 text-[13px] text-muted">
                                    <input type="file" wire:model="attachment" class="max-w-full text-[13px] file:mr-3 file:cursor-pointer file:rounded-md file:border file:border-neutral-line file:bg-neutral file:px-3 file:py-1 file:text-[13px] file:font-semibold file:text-ink">
                                    <button type="button" class="btn btn-n !h-8 !px-3" wire:click="uploadAttachment"
                                        wire:loading.attr="disabled" wire:target="attachment">{{ __('Upload') }}</button>
                                    @error('attachment')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endcan

                            <div class="mt-3.5 flex gap-2.5">
                                <button type="button" class="btn btn-n" wire:click="$set('comment', '')">{{ __('Batal') }}</button>
                                <button type="submit" class="btn btn-p" wire:loading.attr="disabled">{{ __('Membalas') }}</button>
                            </div>
                        </form>
                    </x-thread-message>
                @endif
            </div>
        </x-panel>
    </div>

    {{-- History --}}
    <aside class="max-w-[340px] flex-[1_1_300px] self-start min-[701px]:sticky min-[701px]:top-4" aria-label="{{ __('Riwayat ticket') }}">
        <div class="rounded-lg bg-white px-5 pt-[22px] pb-1.5">
            <h2 class="m-0 text-[22px] font-bold text-ink-strong">{{ $ticket->ticket_number }}</h2>
            <div class="mt-1 text-sm text-muted">{{ __('Riwayat aktivitas') }} · {{ $ticket->title }}</div>

            <div class="relative mt-5 pl-6 before:absolute before:top-1.5 before:bottom-5 before:left-1 before:w-0.5 before:bg-[#d1d5db] before:content-['']">
                @forelse ($histories as $history)
                    @php $isLatest = $history->id === $latestHistoryId; @endphp

                    <div @class([
                        'relative grid grid-cols-[1fr_auto] gap-x-3 gap-y-[3px] pb-5 before:absolute before:top-[5px] before:-left-6 before:size-2.5 before:rounded-full before:content-[\'\']',
                        'before:bg-[#1a73e8]' => $isLatest,
                        'before:bg-[#9ca3af]' => ! $isLatest,
                    ])>
                        <div @class(['text-sm', 'text-link' => $isLatest, 'text-muted' => ! $isLatest])>{{ $history->created_at->format('d M Y') }}</div>
                        <div class="text-right text-[13px] text-muted italic">{{ $history->created_at->format('H:i') }} WIB</div>
                        <div class="text-sm leading-[1.45] text-ink-strong">
                            {{ __($actionLabels[$history->action] ?? ucfirst(str_replace('_', ' ', $history->action))) }}@if ($history->old_value && $history->new_value && $history->old_value !== $history->new_value): {{ $prettify($history->old_value) }} → {{ $prettify($history->new_value) }}@endif
                        </div>
                        <div class="max-w-[110px] text-right text-[13px] text-ink-label italic">{{ $history->performedBy?->name ?? __('System') }}</div>
                    </div>
                @empty
                    <div class="pb-5 text-sm text-muted">{{ __('Belum ada riwayat') }}</div>
                @endforelse
            </div>
        </div>
    </aside>

</div>

    <x-confirm-dialog name="confirm-ticket-delete" />
    <x-confirm-dialog name="confirm-attachment-delete" />

    {{-- Assign agent --}}
    @can('assign', $ticket)
        <flux:modal name="assign-agent" class="w-full max-w-md">
            <form x-data
                x-on:submit.prevent="$wire.assign().then(() => { if (! $wire.assignedUserId) { $flux.modal('assign-agent').close() } })"
                class="flex flex-col gap-4">
                <div>
                    <h3 class="m-0 text-lg font-bold text-ink-strong">{{ $agentName ? __('Ubah Agent') : __('Tugaskan Ticket') }}</h3>
                    <p class="mt-1 mb-0 text-sm text-muted">
                        {{ __('Agent saat ini') }}: <b class="text-ink-strong">{{ $agentName ?? __('Belum ditugaskan') }}</b>
                    </p>
                </div>

                <div>
                    <label for="assigned-user" class="mb-1.5 block text-[11px] font-bold tracking-[.03em] text-ink-label uppercase">{{ __('Agent') }}</label>
                    <select id="assigned-user" wire:model="assignedUserId" class="sel w-full">
                        <option value="">{{ __('Pilih agent...') }}</option>
                        @foreach ($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }} ({{ $agent->department?->name ?? '-' }})</option>
                        @endforeach
                    </select>
                    @error('assignedUserId')
                        <p class="mt-1.5 mb-0 text-[13px] text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-2.5">
                    <button type="button" class="btn btn-n" x-on:click="$flux.modal('assign-agent').close()">{{ __('Batal') }}</button>
                    <button type="submit" class="btn btn-p">{{ $agentName ? __('Ubah Agent') : __('Tugaskan Ticket') }}</button>
                </div>
            </form>
        </flux:modal>
    @endcan

    {{-- Attachment preview --}}
    <flux:modal name="attachment-preview" class="max-w-4xl">
        <div class="p-2">
            <h3 class="mt-0 mb-4 text-lg font-bold text-ink-strong">{{ $previewName }}</h3>

            @if ($previewUrl)
                <img src="{{ $previewUrl }}" alt="{{ $previewName }}" class="mx-auto max-h-[75vh] max-w-full rounded-lg">
            @endif
        </div>
    </flux:modal>
</div>
