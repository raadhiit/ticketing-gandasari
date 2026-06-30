<div>
    <flux:heading size="xl" level="1" class="dark:text-white">{{ __('Buat Ticket Baru') }}</flux:heading>

    <flux:subheading class="mb-6 dark:text-zinc-400">{{ __('Laporkan masalah atau ajukan permintaan') }}</flux:subheading>

    <form wire:submit="save" class="space-y-6 max-w-2xl">
        <flux:card class="p-6 dark:bg-zinc-900 dark:border-zinc-700 space-y-6">
            <flux:field>
                <flux:label class="dark:text-zinc-300">{{ __('Judul') }}</flux:label>
                <flux:input wire:model="title" placeholder="{{ __('Contoh: Email perusahaan tidak bisa dikirim') }}" class="dark:bg-zinc-800 dark:border-zinc-600 dark:text-white dark:placeholder-zinc-500" />
                <flux:error name="title" />
            </flux:field>

            <flux:field>
                <flux:label class="dark:text-zinc-300">{{ __('Deskripsi') }}</flux:label>
                <flux:textarea wire:model="description" rows="5" placeholder="{{ __('Jelaskan masalah secara detail...') }}" class="dark:bg-zinc-800 dark:border-zinc-600 dark:text-white dark:placeholder-zinc-500" />
                <flux:error name="description" />
            </flux:field>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:field>
                    <flux:label class="dark:text-zinc-300">{{ __('Departemen') }}</flux:label>
                    <flux:select wire:model="department_id" class="dark:bg-zinc-800 dark:border-zinc-600 dark:text-white">
                        <option value="">{{ __('Pilih departemen') }}</option>
                        @foreach ($departments as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="department_id" />
                </flux:field>

                <flux:field>
                    <flux:label class="dark:text-zinc-300">{{ __('Kategori') }}</flux:label>
                    <flux:select wire:model="category_id" class="dark:bg-zinc-800 dark:border-zinc-600 dark:text-white">
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
                <flux:select wire:model="priority" class="dark:bg-zinc-800 dark:border-zinc-600 dark:text-white">
                    <option value="LOW">Low</option>
                    <option value="MEDIUM">Medium</option>
                    <option value="HIGH">High</option>
                    <option value="URGENT">Urgent</option>
                </flux:select>
                <flux:error name="priority" />
            </flux:field>
        </flux:card>

        <div class="flex gap-4">
            <flux:button variant="primary" type="submit" wire:loading.attr="disabled">
                {{ __('Kirim Ticket') }}
            </flux:button>

            <flux:button :href="route('tickets.index')" wire:navigate variant="ghost">
                {{ __('Batal') }}
            </flux:button>
        </div>
    </form>
</div>
