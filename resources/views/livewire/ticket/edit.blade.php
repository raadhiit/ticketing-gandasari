<div class="max-w-2xl mx-auto space-y-5">
    <x-confirm-dialog name="confirm-edit" />
    <div class="flex items-center gap-3">
        <flux:button :href="route('tickets.index')" wire:navigate icon="arrow-left" variant="ghost" class="-ms-2 shrink-0">{{ __('Back') }}</flux:button>
        <div>
            <h1 class="text-xl font-semibold tracking-tight text-zinc-900 dark:text-white">{{ __('Edit Ticket') }}</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">{{ $ticket->ticket_number }} — {{ __('Perbarui detail ticket') }}</p>
        </div>
    </div>

    <form class="space-y-5">
        <flux:card class="p-5 border-l-[3px] border-l-blue-500 dark:border-l-blue-400 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card space-y-5">
            <flux:field>
                <flux:label class="dark:text-zinc-300">{{ __('Judul') }}</flux:label>
                <flux:input wire:model="title" placeholder="{{ __('Contoh: Email perusahaan tidak bisa dikirim') }}" class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:placeholder-zinc-500" />
                <flux:error name="title" />
            </flux:field>

            <flux:field>
                <flux:label class="dark:text-zinc-300">{{ __('Deskripsi') }}</flux:label>
                <flux:textarea wire:model="description" rows="5" placeholder="{{ __('Jelaskan masalah secara detail...') }}" class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:placeholder-zinc-500" />
                <flux:error name="description" />
            </flux:field>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:field>
                    <flux:label class="dark:text-zinc-300">{{ __('Departemen') }}</flux:label>
                    <flux:select wire:model="department_id" class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
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
        </flux:card>

        <div class="flex gap-3">
            <flux:button wire:click="confirmSave" variant="primary" wire:loading.attr="disabled">
                {{ __('Simpan Perubahan') }}
            </flux:button>
            <flux:button :href="route('tickets.show', $ticket)" wire:navigate variant="ghost">
                {{ __('Batal') }}
            </flux:button>
        </div>
    </form>
</div>
