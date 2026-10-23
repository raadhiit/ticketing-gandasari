<div class="flex min-w-0 shrink" x-data="{ open: $wire.entangle('open'), position: {} }" @click.outside="open = false">
    <button
        type="button"
        x-ref="trigger"
        @click="if ({{ $sidebar ? 'true' : 'false' }} && !open) { const rect = $refs.trigger.getBoundingClientRect(); position = { top: rect.bottom + 4, left: Math.max(8, Math.min(rect.right + 4, window.innerWidth - 328)) }; }; open = !open; $wire.loadNotifications()"
        class="rail-link" aria-label="{{ $unreadCount > 0 ? __('Notifikasi, :count baru', ['count' => $unreadCount]) : __('Notifikasi') }}" title="{{ __('Notifikasi') }}"
    >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="rail-icon" aria-hidden="true">
            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" />
            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />
        </svg>

        @if ($unreadCount > 0)
            <span class="absolute top-1 right-2 box-border flex h-4 min-w-4 items-center justify-center rounded-full bg-badge px-[3px] text-[10px] leading-none font-bold text-white">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <div
        x-show="open"
        :style="position.top ? `top: ${position.top}px; left: ${position.left}px;` : ''"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed z-50 w-80 max-w-[calc(100vw-16px)] rounded-lg border border-bubble-line bg-white shadow-lg"
        @click.stop
    >
        <div class="flex items-center justify-between px-4 py-3 border-b border-zinc-200 dark:border-zinc-700">
            <span class="text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Notifikasi') }}</span>
            @if ($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-xs text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                    {{ __('Tandai semua dibaca') }}
                </button>
            @endif
        </div>

        <div class="max-h-80 overflow-y-auto">
            @forelse ($notifications as $notification)
                <button
                    wire:click="markAsRead('{{ $notification['id'] }}')"
                    wire:loading.attr="disabled"
                    class="w-full text-start px-4 py-3 hover:bg-zinc-50 dark:hover:bg-zinc-800 border-b border-zinc-100 dark:border-zinc-800 last:border-0 transition-colors"
                >
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-0.5">
                            @if (is_null($notification['read_at']))
                                <span class="block size-2 rounded-full bg-blue-500"></span>
                            @else
                                <span class="block size-2 rounded-full bg-transparent"></span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-zinc-700 dark:text-zinc-300 line-clamp-2 leading-relaxed">
                                {{ $notification['data']['message'] ?? '' }}
                            </p>
                            <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-1">
                                {{ \Illuminate\Support\Carbon::parse($notification['created_at'])->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </button>
            @empty
                <div class="px-4 py-8 text-center text-sm text-zinc-400 dark:text-zinc-500">
                    {{ __('Tidak ada notifikasi') }}
                </div>
            @endforelse
        </div>
    </div>

    <span wire:poll.15s="checkNewNotifications"></span>
</div>
