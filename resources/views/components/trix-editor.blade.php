@props([
    'id' => 'trix-' . \Illuminate\Support\Str::uuid(),
    'placeholder' => '',
])

<div
    wire:ignore
    x-data="{
        value: @entangle($attributes->wire('model')).live,

        init() {
            this.$nextTick(() => {
                this.$refs.input.value = this.value ?? '';
                this.$refs.editor.editor.loadHTML(this.value ?? '');
            });
        },

        sync() {
            this.value = this.$refs.input.value;
        },

        clear() {
            this.value = '';
            this.$refs.input.value = '';
            this.$refs.editor.editor.loadHTML('');
        }
    }"
    x-on:trix-clear.window="
        if (!$event.detail.id || $event.detail.id === '{{ $id }}') {
            clear();
        }
    "
>
    <input
        id="{{ $id }}"
        x-ref="input"
        type="hidden"
    >

    <trix-editor
        x-ref="editor"
        input="{{ $id }}"
        placeholder="{{ $placeholder }}"
        x-on:trix-change="sync()"
        x-on:trix-file-accept.prevent
        class="trix-content min-h-40 rounded-xl border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
    ></trix-editor>
</div>