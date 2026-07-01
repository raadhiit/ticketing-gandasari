@props([
    'name' => 'confirm-dialog',
])

<flux:modal :name="$name" class="max-w-sm p-0! overflow-hidden">
    <div class="text-center"
         x-data="{
            title: '{{ __('Konfirmasi') }}',
            message: '',
            confirmLabel: '{{ __('Ya') }}',
            variant: 'danger',
            method: null,
            param: null,
         }"
         x-on:confirm-open.window="
            if ($event.detail.name === '{{ $name }}') {
                title = $event.detail.title ?? title;
                message = $event.detail.message;
                confirmLabel = $event.detail.confirmLabel ?? confirmLabel;
                variant = $event.detail.variant ?? variant;
                method = $event.detail.method;
                param = $event.detail.param ?? null;
            }
         "
         :class="{
             'bg-gradient-to-b from-red-50/60 to-transparent dark:from-red-950/20': variant === 'danger',
             'bg-gradient-to-b from-amber-50/60 to-transparent dark:from-amber-950/20': variant === 'warning',
             'bg-gradient-to-b from-blue-50/60 to-transparent dark:from-blue-950/20': variant === 'primary',
         }"
    >
        {{-- Colored accent strip --}}
        <div class="h-1"
             :class="{
                 'bg-gradient-to-r from-red-400 to-red-600': variant === 'danger',
                 'bg-gradient-to-r from-amber-400 to-amber-600': variant === 'warning',
                 'bg-gradient-to-r from-blue-400 to-blue-600': variant === 'primary',
             }">
        </div>

        <div class="p-6">
            {{-- Icon --}}
            <template x-if="variant === 'danger'">
                <div class="mx-auto mb-4 size-13 rounded-2xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-lg shadow-red-500/30 ring-1 ring-red-400/20">
                    <svg class="size-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6h18" /><path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" /><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                    </svg>
                </div>
            </template>
            <template x-if="variant === 'warning'">
                <div class="mx-auto mb-4 size-13 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-500/30 ring-1 ring-amber-400/20">
                    <svg class="size-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" /><line x1="12" y1="9" x2="12" y2="13" /><line x1="12" y1="17" x2="12.01" y2="17" />
                    </svg>
                </div>
            </template>
            <template x-if="variant === 'primary'">
                <div class="mx-auto mb-4 size-13 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30 ring-1 ring-blue-400/20">
                    <svg class="size-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" /><polyline points="22 4 12 14.01 9 11.01" />
                    </svg>
                </div>
            </template>

            {{-- Title --}}
            <h3 class="text-base font-semibold text-zinc-900 dark:text-white leading-snug" x-text="title"></h3>

            {{-- Message --}}
            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed" x-text="message"></p>

            {{-- Buttons --}}
            <div class="mt-6 flex flex-col sm:flex-row gap-2.5 justify-center">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Batal') }}</flux:button>
                </flux:modal.close>
                <template x-if="variant === 'danger'">
                    <flux:modal.close>
                        <flux:button variant="danger" x-on:click="param !== null ? $wire.call(method, param) : $wire.call(method)" <span x-text="confirmLabel"</span></flux:button>
                    </flux:modal.close>
                </template>
                <template x-if="variant === 'primary'">
                    <flux:modal.close>
                        <flux:button variant="primary" x-on:click="param !== null ? $wire.call(method, param) : $wire.call(method)" <span x-text="confirmLabel"</span></flux:button>
                    </flux:modal.close>
                </template>
                <template x-if="variant === 'warning'">
                    <flux:modal.close>
                        <flux:button variant="primary" x-on:click="param !== null ? $wire.call(method, param) : $wire.call(method)" <span x-text="confirmLabel"</span></flux:button>
                    </flux:modal.close>
                </template>
            </div>
        </div>
    </div>
</flux:modal>
