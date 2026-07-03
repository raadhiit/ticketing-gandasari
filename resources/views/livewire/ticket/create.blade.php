<div class="max-w-2xl mx-auto space-y-5">
    <x-confirm-dialog name="confirm-create" />
    <div class="flex items-center gap-3">
        <flux:button :href="route('tickets.index')" wire:navigate icon="arrow-left" variant="ghost" class="-ms-2 shrink-0">
            {{ __('Back') }}</flux:button>
        <div>
            <h1 class="text-xl font-semibold tracking-tight text-zinc-900 dark:text-white">{{ __('Buat Ticket Baru') }}
            </h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">
                {{ __('Laporkan masalah atau ajukan permintaan') }}</p>
        </div>
    </div>

    <form class="space-y-5">
        <flux:card
            class="relative overflow-hidden p-5 pl-6 border border-zinc-200 bg-white shadow-card space-y-5 dark:border-zinc-700/50 dark:bg-zinc-900">
            <span aria-hidden="true" class="absolute inset-y-0 left-0 w-1 bg-emerald-500 dark:bg-emerald-400"></span>

            <flux:field>
                <flux:label class="dark:text-zinc-300">
                    {{ __('Judul') }}
                    <span class="ml-0.5 text-red-500 dark:text-red-400">*</span>
                </flux:label>
                <flux:input wire:model="title" placeholder="{{ __('Contoh: Email perusahaan tidak bisa dikirim') }}"
                    class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:placeholder-zinc-500" />
                <flux:error name="title" />
            </flux:field>

            <flux:field>
                <flux:label class="dark:text-zinc-300">
                    {{ __('Deskripsi') }}
                    <span class="ml-0.5 text-red-500 dark:text-red-400">*</span>
                </flux:label>

                <x-trix-editor id="ticket-description-editor" wire:model="description"
                    placeholder="{{ __('Jelaskan masalah secara detail...') }}" />

                <flux:error name="description" />
            </flux:field>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:field>
                    <flux:label class="dark:text-zinc-300">{{ __('Departemen') }}</flux:label>
                    <flux:select wire:model="department_id"
                        class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
                        <option value="">{{ __('Pilih departemen') }}</option>
                        @foreach ($departments as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="department_id" />
                </flux:field>

                <flux:field>
                    <flux:label class="dark:text-zinc-300">
                        {{ __('Kategori') }}
                        <span class="ml-0.5 text-red-500 dark:text-red-400">*</span>
                    </flux:label>
                    <flux:select wire:model="category_id" class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
                        <option value="">{{ __('Pilih kategori') }}</option>
                        @foreach ($categories as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="category_id" />
                </flux:field>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:field>
                    <flux:label class="dark:text-zinc-300">
                        {{ __('Nama Pembuat') }}
                        <span class="ml-0.5 text-red-500 dark:text-red-400">*</span>
                    </flux:label>
                    <flux:input wire:model="requester_name" placeholder="{{ __('Nama lengkap') }}"
                        class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:placeholder-zinc-500" />
                    <flux:error name="requester_name" />
                </flux:field>

                <flux:field>
                    <flux:label class="dark:text-zinc-300">
                        {{ __('Prioritas') }}
                        <span class="ml-0.5 text-red-500 dark:text-red-400">*</span>
                    </flux:label>
                    <flux:select wire:model="priority" class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
                        <option value="LOW">Low</option>
                        <option value="MEDIUM">Medium</option>
                        <option value="HIGH">High</option>
                        <option value="URGENT">Urgent</option>
                    </flux:select>
                    <flux:error name="priority" />
                </flux:field>
            </div>

            <flux:field>
                <flux:label class="dark:text-zinc-300">{{ __('Lampiran') }}</flux:label>

                <input type="file" wire:model="attachments" multiple
                    class="block w-full text-sm text-zinc-600 dark:text-zinc-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200 dark:file:bg-zinc-800 dark:file:text-zinc-300 dark:hover:file:bg-zinc-700" />

                <flux:error name="attachments.*" />

                <div wire:loading wire:target="attachments" class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                    {{ __('Mengupload...') }}
                </div>

                @if ($attachments)
                    <ul class="mt-2 space-y-1">
                        @foreach ($attachments as $index => $file)
                            <li class="text-sm text-zinc-600 dark:text-zinc-400 flex items-center gap-2">
                                <span>{{ $file->getClientOriginalName() }}</span>

                                <button type="button" wire:click="removeAttachment({{ $index }})"
                                    class="text-red-500 hover:text-red-700 text-xs">
                                    &times;
                                </button>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </flux:field>
        </flux:card>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                {{ __('Setelah dikirim, ticket akan masuk ke daftar ticket dan dapat dipantau statusnya.') }}
            </p>

            <div class="flex gap-3">
                <flux:button type="button" variant="primary" wire:target="save,attachments"
                    wire:loading.attr="disabled"
                    x-on:click="
                        $wire.dispatch('confirm-open', {
                            name: 'confirm-create',
                            title: 'Kirim Ticket',
                            message: 'Pastikan data ticket sudah benar sebelum dikirim.',
                            method: 'save',
                            variant: 'primary',
                            confirmLabel: 'Ya, Kirim'
                        });

                        $nextTick(() => $flux.modal('confirm-create').show());
                    ">
                    <span wire:loading.remove wire:target="save">
                        {{ __('Kirim Ticket') }}
                    </span>

                    <span wire:loading wire:target="save">
                        {{ __('Mengirim...') }}
                    </span>
                </flux:button>
            </div>
        </div>
    </form>
</div>
