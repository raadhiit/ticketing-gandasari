<div class="flex flex-col gap-3">
    <div class="tbar" role="toolbar" aria-label="{{ __('Aksi') }}">
        <button type="button" class="tbar-btn" wire:click="create">+ {{ __('Tambah Pengguna') }}</button>
    </div>

    @if ($showForm)
        <x-panel :title="$editId ? __('Edit Pengguna') : __('Tambah Pengguna Baru')">
            <form wire:submit="save">
                <div class="kv kv-form">
                    <div class="kv-k kv-first"><label for="u-name">{{ __('Nama') }}</label><span class="text-required" aria-hidden="true">*</span></div>
                    <div class="kv-v kv-first">
                        <input id="u-name" type="text" wire:model="name" class="inp" placeholder="{{ __('Nama lengkap') }}">
                        @error('name')<p class="hint !text-danger">{{ $message }}</p>@enderror
                    </div>
                    <div class="kv-k kv-first"><label for="u-email">{{ __('Email') }}</label><span class="text-required" aria-hidden="true">*</span></div>
                    <div class="kv-v kv-first">
                        <input id="u-email" type="email" wire:model="email" class="inp" placeholder="email@example.com">
                        @error('email')<p class="hint !text-danger">{{ $message }}</p>@enderror
                    </div>

                    <div class="kv-k"><label for="u-dep">{{ __('Departemen') }}</label></div>
                    <div class="kv-v">
                        <select id="u-dep" wire:model="department_id" class="sel">
                            <option value="">{{ __('Pilih departemen') }}</option>
                            @foreach ($departments as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('department_id')<p class="hint !text-danger">{{ $message }}</p>@enderror
                    </div>
                    <div class="kv-k"><label for="u-role">{{ __('Role') }}</label><span class="text-required" aria-hidden="true">*</span></div>
                    <div class="kv-v">
                        <select id="u-role" wire:model="role" class="sel">
                            @foreach ($roles as $roleName)
                                <option value="{{ $roleName }}">{{ $roleName }}</option>
                            @endforeach
                        </select>
                        @error('role')<p class="hint !text-danger">{{ $message }}</p>@enderror
                    </div>

                    <div class="kv-k"><label for="u-pass">{{ __('Password') }}</label>@unless ($editId)<span class="text-required" aria-hidden="true">*</span>@endunless</div>
                    <div class="kv-v">
                        <input id="u-pass" type="password" wire:model="password" class="inp" autocomplete="new-password">
                        @if ($editId)<p class="hint">{{ __('Kosongkan jika tidak ingin mengubah password.') }}</p>@endif
                        @error('password')<p class="hint !text-danger">{{ $message }}</p>@enderror
                    </div>
                    <div class="kv-k"><label for="u-pass2">{{ __('Konfirmasi Password') }}</label>@unless ($editId)<span class="text-required" aria-hidden="true">*</span>@endunless</div>
                    <div class="kv-v">
                        <input id="u-pass2" type="password" wire:model="password_confirmation" class="inp" autocomplete="new-password">
                        @error('password_confirmation')<p class="hint !text-danger">{{ $message }}</p>@enderror
                    </div>

                    <div class="kv-k"><span>{{ __('Status') }}</span></div>
                    <div class="kv-v kv-span3">
                        <label class="flex items-center gap-2 text-sm font-medium text-ink-label">
                            <input type="checkbox" wire:model="is_active"> {{ __('Pengguna aktif') }}
                        </label>
                        <p class="hint">{{ __('Pengguna nonaktif tidak dapat masuk ke sistem.') }}</p>
                    </div>
                </div>

                <div class="flex justify-end gap-2.5 border-t border-line px-4 py-3.5">
                    <button type="button" class="btn btn-n" wire:click="cancel">{{ __('Batal') }}</button>
                    <button type="submit" class="btn btn-p">{{ $editId ? __('Simpan Perubahan') : __('Simpan Pengguna') }}</button>
                </div>
            </form>
        </x-panel>
    @endif

    <x-panel :title="__('Daftar Pengguna')" :count="$totalUsers">
        <div class="px-4 py-2 text-[13px] text-ink-label">
            {{ $activeUsers }} {{ __('aktif') }} · {{ $inactiveUsers }} {{ __('nonaktif') }}
        </div>

        <div class="overflow-x-auto">
            <table class="tbl min-w-[720px]">
                <thead>
                    <tr>
                        <th>{{ __('Pengguna') }}</th>
                        <th>{{ __('Departemen') }}</th>
                        <th>{{ __('Role') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th class="text-right">{{ __('Aksi') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $user)
                        <tr wire:key="user-{{ $user->id }}">
                            <td>
                                <div class="font-bold text-ink-strong">{{ $user->name }}</div>
                                <div class="text-[13px] text-muted">{{ $user->email }}</div>
                            </td>
                            <td>{{ $user->department?->name ?? '-' }}</td>
                            <td><span class="pill pill-neutral">{{ $user->roles->first()?->name ?? '-' }}</span></td>
                            <td>
                                @if ($user->is_active)
                                    <span class="pill pill-resolved">{{ __('Aktif') }}</span>
                                @else
                                    <span class="pill pill-closed">{{ __('Nonaktif') }}</span>
                                @endif
                            </td>
                            <td class="text-right whitespace-nowrap">
                                <button type="button" class="act" wire:click="edit({{ $user->id }})">{{ __('Edit') }}</button>
                                @if (Auth::id() !== $user->id)
                                    <button type="button" class="act is-danger ml-3.5" wire:click="confirmDelete({{ $user->id }})">{{ __('Hapus') }}</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-muted">{{ __('Belum ada pengguna.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
    </x-panel>

    <x-confirm-dialog name="confirm-delete" />
</div>
