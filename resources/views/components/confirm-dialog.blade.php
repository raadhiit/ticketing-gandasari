@props([
    'name' => 'confirm-dialog',
])

<flux:modal :name="$name" class="w-full max-w-md p-0! overflow-hidden">
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
        class="bg-white"
    >
        <div class="bg-tab px-[22px] py-3.5">
            <h3 class="m-0 text-[15px] font-semibold text-white" x-text="title"></h3>
        </div>

        <div class="px-[22px] pt-[18px] pb-5">
            <p class="m-0 text-sm leading-normal text-ink" x-text="message"></p>

            <div class="mt-5 flex justify-end gap-2.5">
                <flux:modal.close>
                    <button type="button" class="btn btn-n">
                        <span x-text="cancelLabel"></span>
                    </button>
                </flux:modal.close>

                <flux:modal.close>
                    <button type="button" x-on:click="confirm()" class="btn"
                        :class="variant === 'danger' ? 'btn-d' : 'btn-p'">
                        <span x-text="confirmLabel"></span>
                    </button>
                </flux:modal.close>
            </div>
        </div>
    </div>
</flux:modal>
