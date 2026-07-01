<div class="space-y-5">
    <x-confirm-dialog name="confirm-delete" />
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-semibold tracking-tight text-zinc-900 dark:text-white">{{ __('Pengguna') }}</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">{{ __('Kelola pengguna, role, dan hak akses') }}</p>
        </div>
        <flux:button wire:click="create" icon="plus">{{ __('Tambah Pengguna') }}</flux:button>
    </div>

    @if ($showForm)
        <flux:card class="p-5 border-l-[3px] border-l-violet-500 dark:border-l-violet-400 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-white mb-4">{{ $editId ? __('Edit Pengguna') : __('Tambah Pengguna') }}</h2>
            <form wire:submit="save" class="space-y-4 max-w-lg">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label class="dark:text-zinc-300">{{ __('Nama') }}</flux:label>
                        <flux:input wire:model="name" placeholder="{{ __('Nama lengkap') }}" class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:placeholder-zinc-500" />
                        <flux:error name="name" />
                    </flux:field>
                    <flux:field>
                        <flux:label class="dark:text-zinc-300">{{ __('Email') }}</flux:label>
                        <flux:input wire:model="email" type="email" placeholder="{{ __('email@example.com') }}" class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:placeholder-zinc-500" />
                        <flux:error name="email" />
                    </flux:field>
                </div>

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
                        <flux:label class="dark:text-zinc-300">{{ __('Role') }}</flux:label>
                        <flux:select wire:model="role" class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
                            @foreach ($roles as $role)
                                <option value="{{ $role }}">{{ $role }}</option>
                            @endforeach
                        </flux:select>
                        <flux:error name="role" />
                    </flux:field>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label class="dark:text-zinc-300">{{ $editId ? __('Password Baru (opsional)') : __('Password') }}</flux:label>
                        <flux:input wire:model="password" type="password" placeholder="{{ $editId ? __('Kosongkan jika tidak diubah') : '' }}" class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:placeholder-zinc-500" />
                        <flux:error name="password" />
                    </flux:field>
                    <flux:field>
                        <flux:label class="dark:text-zinc-300">{{ __('Konfirmasi Password') }}</flux:label>
                        <flux:input wire:model="password_confirmation" type="password" placeholder="{{ __('Ulangi password') }}" class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:placeholder-zinc-500" />
                        <flux:error name="password_confirmation" />
                    </flux:field>
                </div>

                <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                    <input type="checkbox" wire:model="is_active" class="rounded border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800" />
                    {{ __('Akun aktif') }}
                </label>

                <div class="flex gap-3 pt-2">
                    <flux:button variant="primary" type="submit">{{ __('Simpan') }}</flux:button>
                    <flux:button wire:click="cancel" variant="ghost">{{ __('Batal') }}</flux:button>
                </div>
            </form>
        </flux:card>
    @endif

    <flux:card class="overflow-hidden border-l-[3px] border-l-violet-500/50 dark:border-l-violet-400/50 dark:bg-zinc-900 dark:border-zinc-700/50 shadow-card">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-100 dark:divide-zinc-800">
                <thead>
                    <tr class="border-b border-zinc-100 dark:border-zinc-800">
                        <th class="px-5 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Nama') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Email') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Departemen') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Role') }}</th>
                        <th class="px-5 py-3 text-center text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Status') }}</th>
                        <th class="px-5 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50 dark:divide-zinc-800/50">
                    @forelse ($users as $user)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-5 py-3 text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $user->name }}</td>
                            <td class="px-5 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $user->email }}</td>
                            <td class="px-5 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $user->department?->name ?? '-' }}</td>
                            <td class="px-5 py-3">
                                <flux:badge color="{{ $user->roles->first()?->name === 'Admin' ? 'red' : ($user->roles->first()?->name === 'Agent' ? 'blue' : 'slate') }}" size="sm" class="font-medium">
                                    {{ $user->roles->first()?->name ?? '-' }}
                                </flux:badge>
                            </td>
                            <td class="px-5 py-3 text-center">
                                @if ($user->is_active)
                                    <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">{{ __('Aktif') }}</span>
                                @else
                                    <span class="text-xs text-red-600 dark:text-red-400 font-medium">{{ __('Nonaktif') }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right">
                                <flux:button wire:click="edit({{ $user->id }})" icon="pencil-square" variant="filled" size="sm" class="text-blue-600 dark:text-blue-400"></flux:button>
                                @if (Auth::id() !== $user->id)
                                    <flux:button wire:click="confirmDelete({{ $user->id }})" icon="trash" variant="danger" size="sm"></flux:button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-sm text-zinc-400 dark:text-zinc-500">{{ __('Belum ada pengguna') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </flux:card>
</div>
