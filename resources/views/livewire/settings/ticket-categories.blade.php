<div class="space-y-5">
    <x-confirm-dialog name="confirm-delete" />
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-semibold tracking-tight text-zinc-900 dark:text-white">{{ __('Kategori Ticket') }}</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">{{ __('Kelola kategori ticket') }}</p>
        </div>
        <flux:button wire:click="create" icon="plus">{{ __('Tambah Kategori') }}</flux:button>
    </div>

    @if ($showForm)
        <flux:card class="p-5 border-l-[3px] border-l-emerald-500 dark:border-l-emerald-400 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-white mb-4">{{ $editId ? __('Edit Kategori') : __('Tambah Kategori') }}</h2>
            <form wire:submit="save" class="space-y-4">
                <flux:field>
                    <flux:label class="dark:text-zinc-300">{{ __('Nama') }}</flux:label>
                    <flux:input wire:model="name" placeholder="{{ __('Nama kategori') }}" class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:placeholder-zinc-500" />
                    <flux:error name="name" />
                </flux:field>
                <flux:field>
                    <flux:label class="dark:text-zinc-300">{{ __('Deskripsi') }}</flux:label>
                    <flux:textarea wire:model="description" rows="2" placeholder="{{ __('Deskripsi (opsional)') }}" class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:placeholder-zinc-500" />
                    <flux:error name="description" />
                </flux:field>
                <div class="flex gap-3">
                    <flux:button variant="primary" type="submit">{{ __('Simpan') }}</flux:button>
                    <flux:button wire:click="cancel" variant="ghost">{{ __('Batal') }}</flux:button>
                </div>
            </form>
        </flux:card>
    @endif

    <flux:card class="overflow-hidden border-l-[3px] border-l-emerald-500/50 dark:border-l-emerald-400/50 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-100 dark:divide-zinc-800">
                <thead>
                    <tr class="border-b border-zinc-100 dark:border-zinc-800">
                        <th class="px-5 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Nama') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Deskripsi') }}</th>
                        <th class="px-5 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50 dark:divide-zinc-800/50">
                    @forelse ($categories as $cat)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-5 py-3 text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $cat->name }}</td>
                            <td class="px-5 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $cat->description ?? '-' }}</td>
                            <td class="px-5 py-3 text-right">
                                <flux:button wire:click="edit({{ $cat->id }})" icon="pencil-square" variant="filled" size="sm" class="text-blue-600 dark:text-blue-400"></flux:button>
                                <flux:button wire:click="confirmDelete({{ $cat->id }})" icon="trash" variant="danger" size="sm"></flux:button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-10 text-center text-sm text-zinc-400 dark:text-zinc-500">{{ __('Belum ada kategori') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </flux:card>
</div>
