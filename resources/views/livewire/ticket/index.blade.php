@php
    $user = auth()->user();
    $isSupport = $user->can('ticket.assign');

    $statusChips = [
        '' => ['label' => 'Semua', 'count' => array_sum($counts)],
        'OPEN' => ['label' => 'Open', 'count' => $counts['open']],
        'IN_PROGRESS' => ['label' => 'In Progress', 'count' => $counts['in_progress']],
        'RESOLVED' => ['label' => 'Resolved', 'count' => $counts['resolved']],
        'CLOSED' => ['label' => 'Closed', 'count' => $counts['closed']],
    ];

    $sortArrow = $sortDirection === 'asc' ? '▲' : '▼';
@endphp

<div class="flex flex-col gap-3" wire:poll.20s="checkNewTickets">
    <div class="tbar" role="toolbar" aria-label="{{ __('Aksi') }}">
        <a href="{{ route('tickets.create') }}" wire:navigate class="tbar-btn">+ {{ __('Buat Ticket') }}</a>
    </div>

    @if ($hasNewTickets)
        <div class="notice">{{ __('Ada ticket baru. Halaman sudah diperbarui.') }}</div>
    @endif

    <section class="panel" aria-label="{{ __('Daftar ticket') }}">
        <div class="panel-strip">
            @if ($isSupport)
                <button type="button" wire:click="$set('mine', false)" @class(['panel-tab cursor-pointer border-0', 'is-off' => $mine]) @unless ($mine) aria-current="page" @endunless>{{ __('Semua Ticket') }}</button>
                <button type="button" wire:click="$set('mine', true)" @class(['panel-tab cursor-pointer border-0', 'is-off' => ! $mine]) @if ($mine) aria-current="page" @endif>{{ __('Ticket Saya') }}</button>
            @else
                <div class="panel-tab" aria-current="page">{{ __('Ticket Saya') }}</div>
            @endif
        </div>

        <div class="flex flex-wrap items-center gap-x-3.5 gap-y-2.5 border-b border-line px-4 py-3">
            <label for="q" class="sr-only">{{ __('Cari ticket') }}</label>
            <input id="q" type="search" wire:model.live.debounce.300ms="search" class="inp w-[280px] max-w-full"
                placeholder="{{ __('Cari nomor ticket atau judul...') }}">

            <label for="fs" class="sr-only">{{ __('Status') }}</label>
            <select id="fs" wire:model.live="statusFilter" class="sel">
                <option value="">{{ __('Semua Status') }}</option>
                <option value="OPEN">Open</option>
                <option value="IN_PROGRESS">In Progress</option>
                <option value="RESOLVED">Resolved</option>
                <option value="CLOSED">Closed</option>
            </select>

            <label for="fp" class="sr-only">{{ __('Prioritas') }}</label>
            <select id="fp" wire:model.live="priorityFilter" class="sel">
                <option value="">{{ __('Semua Prioritas') }}</option>
                <option value="LOW">Low</option>
                <option value="MEDIUM">Medium</option>
                <option value="HIGH">High</option>
                <option value="URGENT">Urgent</option>
            </select>

            <button type="button" wire:click="refresh" class="cursor-pointer border-0 bg-transparent p-0 text-sm font-semibold text-link hover:text-link-hover">{{ __('Refresh') }}</button>
        </div>

        <div class="flex flex-wrap gap-1.5 border-b border-line bg-[#fafbfc] px-4 py-2.5" role="group" aria-label="{{ __('Filter cepat status') }}">
            @foreach ($statusChips as $value => $chip)
                @php $on = $statusFilter === (string) $value; @endphp

                <button type="button" wire:click="$set('statusFilter', '{{ $value }}')" @if ($on) aria-current="true" @endif
                    @class([
                        'cursor-pointer rounded-[14px] border px-3 py-[5px] text-[13px] font-semibold',
                        'border-tab bg-tab text-white' => $on,
                        'border-[#cbd5e1] bg-white text-ink hover:bg-[#eef2f8]' => ! $on,
                    ])>
                    {{ $chip['label'] }}<span class="ml-1 font-medium opacity-80">{{ $chip['count'] }}</span>
                </button>
            @endforeach
        </div>

        @if ($activeDepartment || $activeCategory)
            <div class="flex flex-wrap items-center gap-2 border-b border-line px-4 py-2 text-[13px] text-ink-label">
                <span class="font-semibold">{{ __('Filter aktif') }}:</span>

                @if ($activeDepartment)
                    <button type="button" wire:click="$set('departmentFilter', '')" class="cursor-pointer rounded-[14px] border border-[#cbd5e1] bg-white px-2.5 py-0.5 text-[13px] font-medium text-ink hover:bg-[#eef2f8]" aria-label="{{ __('Hapus filter departemen') }}">
                        {{ __('Departemen') }}: {{ $activeDepartment }} ✕
                    </button>
                @endif

                @if ($activeCategory)
                    <button type="button" wire:click="$set('categoryFilter', '')" class="cursor-pointer rounded-[14px] border border-[#cbd5e1] bg-white px-2.5 py-0.5 text-[13px] font-medium text-ink hover:bg-[#eef2f8]" aria-label="{{ __('Hapus filter kategori') }}">
                        {{ __('Kategori') }}: {{ $activeCategory }} ✕
                    </button>
                @endif
            </div>
        @endif

        <div class="px-4 py-2 text-[13px] text-ink-label">
            @if ($tickets->total() > 0)
                {{ __('Menampilkan :first - :last dari :total ticket.', ['first' => $tickets->firstItem(), 'last' => $tickets->lastItem(), 'total' => $tickets->total()]) }}
                {{ __('Klik judul atau nomor untuk membuka detail.') }}
            @else
                {{ __('Tidak ada ticket yang sesuai.') }}
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="tbl min-w-[1180px]">
                <thead>
                    <tr>
                        @foreach (['ticket_number' => 'No. Ticket', 'title' => 'Judul'] as $field => $label)
                            <th>
                                <button type="button" wire:click="sortBy('{{ $field }}')" class="cursor-pointer border-0 bg-transparent p-0 uppercase hover:text-link">{{ __($label) }}@if ($sortField === $field) {{ $sortArrow }}@endif</button>
                            </th>
                        @endforeach
                        <th>{{ __('Pelapor') }}</th>
                        <th>{{ __('Departemen') }}</th>
                        <th>{{ __('Kategori') }}</th>
                        @foreach (['priority' => 'Prioritas', 'status' => 'Status'] as $field => $label)
                            <th>
                                <button type="button" wire:click="sortBy('{{ $field }}')" class="cursor-pointer border-0 bg-transparent p-0 uppercase hover:text-link">{{ __($label) }}@if ($sortField === $field) {{ $sortArrow }}@endif</button>
                            </th>
                        @endforeach
                        <th>{{ __('Ditangani oleh') }}</th>
                        <th>
                            <button type="button" wire:click="sortBy('created_at')" class="cursor-pointer border-0 bg-transparent p-0 uppercase hover:text-link" aria-label="{{ __('Urut Dibuat') }}">{{ __('Dibuat') }}@if ($sortField === 'created_at') {{ $sortArrow }}@endif</button>
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($tickets as $ticket)
                        @php $agentName = $ticket->activeAssignment?->assignedTo?->name; @endphp

                        <tr wire:key="ticket-{{ $ticket->id }}">
                            <td class="whitespace-nowrap"><a href="{{ route('tickets.show', $ticket) }}" wire:navigate>{{ $ticket->ticket_number }}</a></td>
                            <td><a href="{{ route('tickets.show', $ticket) }}" wire:navigate class="!font-semibold !text-ink-strong">{{ $ticket->title }}</a></td>
                            <td>{{ $ticket->requester_name ?: $ticket->requester?->name }}</td>
                            <td>{{ $ticket->department?->name }}</td>
                            <td>{{ $ticket->category?->name }}</td>
                            <td class="whitespace-nowrap"><x-priority-dot :priority="$ticket->priority" /></td>
                            <td><x-status-pill :status="$ticket->status" /></td>
                            <td class="whitespace-nowrap">
                                @if ($agentName)
                                    {{ $agentName }}
                                @else
                                    <span class="text-muted">{{ __('Belum ditugaskan') }}</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap text-muted">{{ $ticket->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-muted">{{ __('Belum ada ticket.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $tickets->links() }}
    </section>
</div>
