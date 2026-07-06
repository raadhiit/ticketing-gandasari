@props([
    'id' => 'trix-' . \Illuminate\Support\Str::uuid(),
    'placeholder' => '',
    'uploadUrl' => route('trix.attachments.store'),
])

<div
    wire:ignore
    x-data="{
        value: @entangle($attributes->wire('model')),

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
        },

        validateFile(event) {
            const file = event.file;

            if (!file) return;

            const isImage = file.type.startsWith('image/');
            const maxSize = 5 * 1024 * 1024;

            if (!isImage) {
                event.preventDefault();
                alert('Hanya file gambar yang bisa dipaste ke editor.');
                return;
            }

            if (file.size > maxSize) {
                event.preventDefault();
                alert('Ukuran gambar maksimal 5MB.');
            }
        },

        uploadAttachment(attachment) {
            if (!attachment.file) return;

            const file = attachment.file;

            if (!file.type.startsWith('image/')) {
                attachment.remove();
                alert('Hanya file gambar yang bisa dipaste ke editor.');
                return;
            }

            const csrfToken = document.querySelector('meta[name=csrf-token]')?.getAttribute('content');

            if (!csrfToken) {
                attachment.remove();
                alert('CSRF token tidak ditemukan.');
                return;
            }

            const formData = new FormData();
            formData.append('attachment', file);

            const xhr = new XMLHttpRequest();

            xhr.open('POST', '{{ $uploadUrl }}', true);
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            xhr.setRequestHeader('Accept', 'application/json');

            xhr.upload.addEventListener('progress', function (event) {
                if (!event.lengthComputable) return;

                const progress = Math.round((event.loaded / event.total) * 100);
                attachment.setUploadProgress(progress);
            });

            xhr.onload = function () {
                if (xhr.status < 200 || xhr.status >= 300) {
                    attachment.remove();
                    alert('Gagal upload gambar.');
                    return;
                }

                const response = JSON.parse(xhr.responseText);

                attachment.setAttributes({
                    url: response.url,
                    href: response.href ?? response.url,
                });
            };

            xhr.onerror = function () {
                attachment.remove();
                alert('Gagal upload gambar.');
            };

            xhr.send(formData);
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
        x-on:trix-file-accept="validateFile($event)"
        x-on:trix-attachment-add="uploadAttachment($event.attachment)"
        class="trix-content min-h-40 rounded-xl border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
    ></trix-editor>
</div>