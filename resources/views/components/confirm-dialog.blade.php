@props([
    'name' => 'confirm-dialog',
])

<flux:modal :name="$name" class="max-w-md p-0! overflow-hidden">
    <div
        x-data="{
            title: @js(__('Konfirmasi')),
            message: '',
            confirmLabel: @js(__('Ya')),
            cancelLabel: @js(__('Batal')),
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
            },
        }"
        x-on:confirm-open.window="
            if ($event.detail.name === '{{ $name }}') {
                title = $event.detail.title ?? @js(__('Konfirmasi'));
                message = $event.detail.message ?? '';
                confirmLabel = $event.detail.confirmLabel ?? @js(__('Ya'));
                cancelLabel = $event.detail.cancelLabel ?? @js(__('Batal'));
                variant = $event.detail.variant ?? 'danger';
                method = $event.detail.method ?? null;
                param = $event.detail.param ?? null;
            }
        "
        class="relative overflow-hidden bg-white dark:bg-zinc-900"
    >
        {{-- Top accent --}}
        <div
            class="h-1.5"
            :class="{
                'bg-gradient-to-r from-rose-400 via-rose-500 to-red-600': variant === 'danger',
                'bg-gradient-to-r from-amber-300 via-amber-500 to-orange-500': variant === 'warning',
                'bg-gradient-to-r from-teal-400 via-emerald-500 to-teal-600': variant === 'primary',
                'bg-gradient-to-r from-sky-400 via-blue-500 to-indigo-500': variant === 'info',
            }"
        ></div>

        {{-- Soft background glow --}}
        <div
            aria-hidden="true"
            class="pointer-events-none absolute inset-x-0 top-0 h-32"
            :class="{
                'bg-gradient-to-b from-rose-50 to-transparent dark:from-rose-950/25': variant === 'danger',
                'bg-gradient-to-b from-amber-50 to-transparent dark:from-amber-950/25': variant === 'warning',
                'bg-gradient-to-b from-emerald-50 to-transparent dark:from-emerald-950/25': variant === 'primary',
                'bg-gradient-to-b from-sky-50 to-transparent dark:from-sky-950/25': variant === 'info',
            }"
        ></div>

        <div class="relative p-6">
            <div class="flex gap-4">
                {{-- Icon --}}
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl ring-1 ring-inset"
                    :class="{
                        'bg-rose-50 text-rose-600 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-300 dark:ring-rose-500/30': variant === 'danger',
                        'bg-amber-50 text-amber-600 ring-amber-200 dark:bg-amber-500/10 dark:text-amber-300 dark:ring-amber-500/30': variant === 'warning',
                        'bg-emerald-50 text-emerald-600 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-500/30': variant === 'primary',
                        'bg-sky-50 text-sky-600 ring-sky-200 dark:bg-sky-500/10 dark:text-sky-300 dark:ring-sky-500/30': variant === 'info',
                    }"
                >
                    {{-- Danger icon --}}
                    <svg
                        x-show="variant === 'danger'"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" />
                    </svg>

                    {{-- Warning icon --}}
                    <svg
                        x-show="variant === 'warning'"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0Z" />
                    </svg>

                    {{-- Primary icon --}}
                    <svg
                        x-show="variant === 'primary'"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>

                    {{-- Info icon --}}
                    <svg
                        x-show="variant === 'info'"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 17v-5m0-4h.01M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0Z" />
                    </svg>
                </div>

                {{-- Content --}}
                <div class="min-w-0 flex-1 pt-0.5">
                    <h3
                        class="text-base font-semibold leading-6 text-zinc-950 dark:text-white"
                        x-text="title"
                    ></h3>

                    <p
                        class="mt-2 text-sm leading-6 text-zinc-600 dark:text-zinc-400"
                        x-text="message"
                    ></p>
                </div>
            </div>

            {{-- Action buttons --}}
            <div class="mt-6 flex flex-col-reverse gap-2.5 sm:flex-row sm:justify-end">
                <flux:modal.close>
                    <flux:button
                        type="button"
                        variant="ghost"
                        class="sm:min-w-24"
                    >
                        <span x-text="cancelLabel"></span>
                    </flux:button>
                </flux:modal.close>

                <flux:modal.close>
                    <button
                        type="button"
                        x-on:click="confirm()"
                        class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 sm:min-w-28"
                        :class="{
                            'bg-rose-600 hover:bg-rose-700 focus:ring-rose-500': variant === 'danger',
                            'bg-amber-600 hover:bg-amber-700 focus:ring-amber-500': variant === 'warning',
                            'bg-teal-700 hover:bg-teal-800 focus:ring-teal-600': variant === 'primary',
                            'bg-sky-600 hover:bg-sky-700 focus:ring-sky-500': variant === 'info',
                        }"
                    >
                        <span x-text="confirmLabel"></span>
                    </button>
                </flux:modal.close>
            </div>
        </div>
    </div>
</flux:modal>