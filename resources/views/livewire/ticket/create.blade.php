<div>
    <flux:heading size="xl" level="1">{{ __('Buat Ticket Baru') }}</flux:heading>

    <flux:subheading class="mb-6">{{ __('Laporkan masalah atau ajukan permintaan') }}</flux:subheading>

    <form wire:submit="save" class="space-y-6 max-w-2xl">
        <flux:field>
            <flux:label>{{ __('Judul') }}</flux:label>
            <flux:input wire:model="title" placeholder="{{ __('Contoh: Email perusahaan tidak bisa dikirim') }}" />
            <flux:error name="title" />
        </flux:field>

        <flux:field>
            <flux:label>{{ __('Deskripsi') }}</flux:label>
            <flux:textarea wire:model="description" rows="5" placeholder="{{ __('Jelaskan masalah secara detail...') }}" />
            <flux:error name="description" />
        </flux:field>

        <div class="grid grid-cols-2 gap-4">
            <flux:field>
                <flux:label>{{ __('Departemen') }}</flux:label>
                <flux:select wire:model="department_id">
                    <option value="">{{ __('Pilih departemen') }}</option>
                    @foreach ($departments as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </flux:select>
                <flux:error name="department_id" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Kategori') }}</flux:label>
                <flux:select wire:model="category_id">
                    <option value="">{{ __('Pilih kategori') }}</option>
                    @foreach ($categories as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </flux:select>
                <flux:error name="category_id" />
            </flux:field>
        </div>

        <flux:field>
            <flux:label>{{ __('Prioritas') }}</flux:label>
            <flux:select wire:model="priority">
                <option value="LOW">Low</option>
                <option value="MEDIUM">Medium</option>
                <option value="HIGH">High</option>
                <option value="URGENT">Urgent</option>
            </flux:select>
            <flux:error name="priority" />
        </flux:field>

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
