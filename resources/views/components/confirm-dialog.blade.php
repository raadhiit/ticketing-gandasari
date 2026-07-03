@props([
    'name' => 'confirm-dialog',
])

<flux:modal :name="$name" class="max-w-sm p-0! overflow-hidden">
    <div
        class="text-center"
        x-data="{
            title: '{{ __('Konfirmasi') }}',
            message: '',
            confirmLabel: '{{ __('Ya') }}',
            variant: 'danger',
            method: null,
            param: null,

            confirm() {
                if (! this.method) return;

                if (this.param !== null) {
                    $wire.call(this.method, this.param);
                    return;
                }

                $wire.call(this.method);
            }
        }"
        x-on:confirm-open.window="
            if ($event.detail.name === '{{ $name }}') {
                title = $event.detail.title ?? '{{ __('Konfirmasi') }}';
                message = $event.detail.message ?? '';
                confirmLabel = $event.detail.confirmLabel ?? '{{ __('Ya') }}';
                variant = $event.detail.variant ?? 'danger';
                method = $event.detail.method ?? null;
                param = $event.detail.param ?? null;
            }
        "
        :class="{
            'bg-gradient-to-b from-red-50/60 to-transparent dark:from-red-950/20': variant === 'danger',
            'bg-gradient-to-b from-amber-50/60 to-transparent dark:from-amber-950/20': variant === 'warning',
            'bg-gradient-to-b from-blue-50/60 to-transparent dark:from-blue-950/20': variant === 'primary',
        }"
    >
        <div
            class="h-1"
            :class="{
                'bg-gradient-to-r from-red-400 to-red-600': variant === 'danger',
                'bg-gradient-to-r from-amber-400 to-amber-600': variant === 'warning',
                'bg-gradient-to-r from-blue-400 to-blue-600': variant === 'primary',
            }"
        ></div>

        <div class="p-6">
            <h3 class="text-base font-semibold leading-snug text-zinc-900 dark:text-white" x-text="title"></h3>

            <p class="mt-2 text-sm leading-relaxed text-zinc-500 dark:text-zinc-400" x-text="message"></p>

            <div class="mt-6 flex flex-col justify-center gap-2.5 sm:flex-row">
                <flux:modal.close>
                    <flux:button variant="filled">
                        {{ __('Batal') }}
                    </flux:button>
                </flux:modal.close>

                <flux:modal.close>
                    <button
                        type="button"
                        x-on:click="confirm()"
                        class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-semibold text-white shadow-sm transition"
                        :class="{
                            'bg-red-600 hover:bg-red-700': variant === 'danger',
                            'bg-amber-600 hover:bg-amber-700': variant === 'warning',
                            'bg-blue-600 hover:bg-blue-700': variant === 'primary',
                        }"
                    >
                        <span x-text="confirmLabel"></span>
                    </button>
                </flux:modal.close>
            </div>
        </div>
    </div>
</flux:modal>