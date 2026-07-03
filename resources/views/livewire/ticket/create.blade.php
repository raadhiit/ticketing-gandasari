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
            class="p-5 border-l-[3px] border-l-emerald-500 dark:border-l-emerald-400 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card space-y-5">
            <flux:field>
                <flux:label class="dark:text-zinc-300">{{ __('Judul') }}</flux:label>
                <flux:input wire:model="title" placeholder="{{ __('Contoh: Email perusahaan tidak bisa dikirim') }}"
                    class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:placeholder-zinc-500" />
                <flux:error name="title" />
            </flux:field>

            <flux:field>
                <flux:label class="dark:text-zinc-300">{{ __('Deskripsi') }}</flux:label>
                {{-- <flux:textarea wire:model="description" rows="5" placeholder="{{ __('Jelaskan masalah secara detail...') }}" class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:placeholder-zinc-500" /> --}}
                <x-trix-editor id="ticket-description-editor" wire:model="description"
                    placeholder="{{ __('Jelaskan masalah secara detail...') }}" />

                <flux:error name="description" />
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
                    <flux:label class="dark:text-zinc-300">{{ __('Kategori') }}</flux:label>
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
                    <flux:label class="dark:text-zinc-300">{{ __('Nama Pembuat') }}</flux:label>
                    <flux:input wire:model="requester_name" placeholder="{{ __('Nama lengkap') }}"
                        class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:placeholder-zinc-500" />
                    <flux:error name="requester_name" />
                </flux:field>

                <flux:field>
                    <flux:label class="dark:text-zinc-300">{{ __('Prioritas') }}</flux:label>
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
                    class="block w-full text-sm text-zinc-600 dark:text-zinc-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-zinc-100 file:text-zinc-700 dark:file:bg-zinc-800 dark:file:text-zinc-300 hover:file:bg-zinc-200 dark:hover:file:bg-zinc-700" />
                <flux:error name="attachments.*" />
                <div wire:loading wire:target="attachments" class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                    {{ __('Mengupload...') }}</div>
                @if ($attachments)
                    <ul class="mt-2 space-y-1">
                        @foreach ($attachments as $index => $file)
                            <li class="text-sm text-zinc-600 dark:text-zinc-400 flex items-center gap-2">
                                <span>{{ $file->getClientOriginalName() }}</span>
                                <button type="button" wire:click="removeAttachment({{ $index }})"
                                    class="text-red-500 hover:text-red-700 text-xs">&times;</button>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </flux:field>
        </flux:card>

        <div class="flex gap-3">
            <flux:button wire:click="confirmSave" variant="primary" wire:loading.attr="disabled">
                {{ __('Kirim Ticket') }}
            </flux:button>
            <flux:button :href="route('tickets.index')" wire:navigate variant="ghost">
                {{ __('Batal') }}
            </flux:button>
        </div>
    </form>
</div>
