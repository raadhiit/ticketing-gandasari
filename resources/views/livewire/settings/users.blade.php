@php
    $roleBadge = function (?string $role) {
        return match ($role) {
            'superadmin'
                => 'bg-rose-50 text-rose-700 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-300 dark:ring-rose-500/20',
            'IT ERP'
                => 'bg-blue-50 text-blue-700 ring-blue-200 dark:bg-blue-500/10 dark:text-blue-300 dark:ring-blue-500/20',
            'User' => 'bg-zinc-50 text-zinc-700 ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-700',
            default
                => 'bg-violet-50 text-violet-700 ring-violet-200 dark:bg-violet-500/10 dark:text-violet-300 dark:ring-violet-500/20',
        };
    };
@endphp

<div class="space-y-6">
    <x-confirm-dialog name="confirm-delete" />

    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white md:text-3xl">
                {{ __('Pengguna') }}
            </h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400 md:text-base">
                {{ __('Kelola pengguna, role, departemen, dan status akses akun') }}
            </p>
        </div>

        <flux:button wire:click="create" icon="plus" class="h-11 rounded-2xl px-5 shadow-sm">
            {{ __('Tambah Pengguna') }}
        </flux:button>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <div
            class="rounded-3xl border border-blue-200/70 bg-white p-5 shadow-sm dark:border-blue-500/20 dark:bg-zinc-900">
            <div class="flex items-start justify-between gap-4">
                <div
                    class="flex size-14 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-600/25">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.964 0A9 9 0 1 0 6.018 18.725m11.964 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </div>

                <div class="text-right">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-600 dark:text-blue-400">
                        {{ __('Total Pengguna') }}
                    </p>
                    <p class="mt-1 text-4xl font-bold leading-none text-zinc-900 dark:text-white">
                        {{ $totalUsers }}
                    </p>
                </div>
            </div>

            <p class="mt-6 text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('Semua akun yang terdaftar') }}
            </p>
        </div>

        <div
            class="rounded-3xl border border-emerald-200/70 bg-white p-5 shadow-sm dark:border-emerald-500/20 dark:bg-zinc-900">
            <div class="flex items-start justify-between gap-4">
                <div
                    class="flex size-14 items-center justify-center rounded-2xl bg-emerald-500 text-white shadow-lg shadow-emerald-500/25">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>

                <div class="text-right">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-600 dark:text-emerald-400">
                        {{ __('Aktif') }}
                    </p>
                    <p class="mt-1 text-4xl font-bold leading-none text-zinc-900 dark:text-white">
                        {{ $activeUsers }}
                    </p>
                </div>
            </div>

            <p class="mt-6 text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('Akun yang dapat digunakan') }}
            </p>
        </div>

        <div
            class="rounded-3xl border border-rose-200/70 bg-white p-5 shadow-sm dark:border-rose-500/20 dark:bg-zinc-900">
            <div class="flex items-start justify-between gap-4">
                <div
                    class="flex size-14 items-center justify-center rounded-2xl bg-rose-500 text-white shadow-lg shadow-rose-500/25">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>

                <div class="text-right">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-rose-600 dark:text-rose-400">
                        {{ __('Nonaktif') }}
                    </p>
                    <p class="mt-1 text-4xl font-bold leading-none text-zinc-900 dark:text-white">
                        {{ $inactiveUsers }}
                    </p>
                </div>
            </div>

            <p class="mt-6 text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('Akun yang dibatasi aksesnya') }}
            </p>
        </div>
    </div>

    {{-- Form --}}
    @if ($showForm)
        <div
            class="overflow-hidden rounded-3xl border border-violet-200 bg-white shadow-sm dark:border-violet-500/20 dark:bg-zinc-900">
            <div class="flex items-start gap-4 border-b border-zinc-100 px-5 py-5 dark:border-zinc-800">
                <div
                    class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-violet-600 text-white shadow-lg shadow-violet-600/20">
                    @if ($editId)
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    @endif
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">
                        {{ $editId ? __('Edit Pengguna') : __('Tambah Pengguna Baru') }}
                    </h2>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                        {{ $editId ? __('Perbarui data pengguna, role, departemen, atau status akun') : __('Buat akun baru dan tentukan role serta departemennya') }}
                    </p>
                </div>
            </div>

            <form wire:submit="save" class="space-y-5 p-5">
                <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                    <flux:field>
                        <flux:label>{{ __('Nama') }}</flux:label>
                        <flux:input wire:model="name" placeholder="{{ __('Nama lengkap') }}"
                            class="h-12 rounded-2xl dark:bg-zinc-950 dark:border-zinc-700/50 dark:text-white dark:placeholder-zinc-500" />
                        <flux:error name="name" />
                    </flux:field>

                    <flux:field>
                        <flux:label>{{ __('Email') }}</flux:label>
                        <flux:input wire:model="email" type="email" placeholder="{{ __('email@example.com') }}"
                            class="h-12 rounded-2xl dark:bg-zinc-950 dark:border-zinc-700/50 dark:text-white dark:placeholder-zinc-500" />
                        <flux:error name="email" />
                    </flux:field>
                </div>

                <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                    <flux:field>
                        <flux:label>{{ __('Departemen') }}</flux:label>
                        <flux:select wire:model="department_id"
                            class="h-12 rounded-2xl dark:bg-zinc-950 dark:border-zinc-700/50 dark:text-white">
                            <option value="">{{ __('Pilih departemen') }}</option>
                            @foreach ($departments as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </flux:select>
                        <flux:error name="department_id" />
                    </flux:field>

                    <flux:field>
                        <flux:label>{{ __('Role') }}</flux:label>
                        <flux:select wire:model="role"
                            class="h-12 rounded-2xl dark:bg-zinc-950 dark:border-zinc-700/50 dark:text-white">
                            @foreach ($roles as $role)
                                <option value="{{ $role }}">{{ $role }}</option>
                            @endforeach
                        </flux:select>
                        <flux:error name="role" />
                    </flux:field>
                </div>

                <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                    <flux:field>
                        <flux:label>
                            {{ $editId ? __('Password Baru (opsional)') : __('Password') }}
                        </flux:label>
                        <flux:input wire:model="password" type="password"
                            placeholder="{{ $editId ? __('Kosongkan jika tidak diubah') : __('Minimal 8 karakter') }}"
                            class="h-12 rounded-2xl dark:bg-zinc-950 dark:border-zinc-700/50 dark:text-white dark:placeholder-zinc-500" />
                        <flux:error name="password" />
                    </flux:field>

                    <flux:field>
                        <flux:label>{{ __('Konfirmasi Password') }}</flux:label>
                        <flux:input wire:model="password_confirmation" type="password"
                            placeholder="{{ __('Ulangi password') }}"
                            class="h-12 rounded-2xl dark:bg-zinc-950 dark:border-zinc-700/50 dark:text-white dark:placeholder-zinc-500" />
                        <flux:error name="password_confirmation" />
                    </flux:field>
                </div>

                <label
                    class="flex items-center justify-between rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-3 dark:border-zinc-800 dark:bg-zinc-950/50">
                    <div>
                        <p class="text-sm font-medium text-zinc-900 dark:text-white">
                            {{ __('Akun aktif') }}
                        </p>
                        <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">
                            {{ __('Jika nonaktif, user seharusnya tidak bisa mengakses sistem') }}
                        </p>
                    </div>

                    <input type="checkbox" wire:model="is_active"
                        class="size-5 rounded border-zinc-300 text-blue-600 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900" />
                </label>

                <div
                    class="flex flex-col-reverse gap-3 border-t border-zinc-100 pt-5 dark:border-zinc-800 sm:flex-row sm:justify-end">
                    <flux:button type="button" wire:click="cancel" variant="ghost" class="rounded-2xl">
                        {{ __('Batal') }}
                    </flux:button>

                    <flux:button variant="primary" type="submit" class="rounded-2xl px-5">
                        {{ $editId ? __('Simpan Perubahan') : __('Simpan Pengguna') }}
                    </flux:button>
                </div>
            </form>
        </div>
    @endif

    {{-- User List --}}
    <div
        class="overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div
            class="flex flex-col gap-3 border-b border-zinc-100 px-5 py-5 dark:border-zinc-800 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                    {{ __('Daftar Pengguna') }}
                </h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    {{ __('Pantau akun, role, departemen, dan status akses pengguna') }}
                </p>
            </div>

            <span
                class="inline-flex w-fit items-center rounded-2xl bg-zinc-50 px-3 py-1.5 text-xs font-semibold text-zinc-600 ring-1 ring-inset ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-700">
                {{ $totalUsers }} {{ __('pengguna') }}
            </span>
        </div>

        {{-- Desktop Table --}}
        <div class="hidden overflow-x-auto lg:block">
            <table class="min-w-full">
                <thead class="bg-zinc-50/80 dark:bg-zinc-950/60">
                    <tr class="border-b border-zinc-100 dark:border-zinc-800">
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                            {{ __('Pengguna') }}
                        </th>
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                            {{ __('Departemen') }}
                        </th>
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                            {{ __('Role') }}
                        </th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                            {{ __('Status') }}
                        </th>
                        <th
                            class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                            {{ __('Aksi') }}
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse ($users as $user)
                        @php
                            $roleName = $user->roles->first()?->name ?? '-';
                        @endphp

                        <tr class="transition hover:bg-zinc-50 dark:hover:bg-zinc-800/40">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-violet-50 text-sm font-bold text-violet-700 dark:bg-violet-500/10 dark:text-violet-300">
                                        {{ $user->initials() }}
                                    </div>

                                    <div>
                                        <p class="text-sm font-semibold text-zinc-900 dark:text-white">
                                            {{ $user->name }}
                                        </p>
                                        <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">
                                            {{ $user->email }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                                {{ $user->department?->name ?? '-' }}
                            </td>

                            <td class="px-5 py-4">
                                <span
                                    class="inline-flex items-center rounded-xl px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $roleBadge($roleName) }}">
                                    {{ $roleName }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-center">
                                @if ($user->is_active)
                                    <span
                                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-500/20">
                                        <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                        {{ __('Aktif') }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-2 rounded-xl bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700 ring-1 ring-inset ring-rose-200 dark:bg-rose-500/10 dark:text-rose-300 dark:ring-rose-500/20">
                                        <span class="size-1.5 rounded-full bg-rose-500"></span>
                                        {{ __('Nonaktif') }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <flux:button wire:click="edit({{ $user->id }})" icon="pencil-square"
                                        variant="filled" size="sm"
                                        class="rounded-xl text-blue-600 dark:text-blue-400">
                                        {{ __('Edit') }}
                                    </flux:button>

                                    @if (Auth::id() !== $user->id)
                                        <flux:button wire:click="confirmDelete({{ $user->id }})" icon="trash"
                                            variant="danger" size="sm" class="rounded-xl">
                                            {{ __('Hapus') }}
                                        </flux:button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-16">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <div class="relative mb-5">
                                        <div
                                            class="absolute inset-0 rounded-full bg-violet-400/20 blur-2xl dark:bg-violet-400/10">
                                        </div>
                                        <div
                                            class="relative flex size-24 items-center justify-center rounded-[2rem] bg-gradient-to-br from-violet-100 to-blue-100 text-violet-600 dark:from-violet-500/10 dark:to-blue-500/10 dark:text-violet-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-12" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.964 0A9 9 0 1 0 6.018 18.725m11.964 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </div>
                                    </div>

                                    <h3 class="text-xl font-semibold text-zinc-900 dark:text-white">
                                        {{ __('Belum ada pengguna') }}
                                    </h3>
                                    <p class="mt-2 max-w-md text-sm leading-6 text-zinc-500 dark:text-zinc-400">
                                        {{ __('Tambahkan pengguna pertama agar sistem bisa digunakan oleh tim.') }}
                                    </p>

                                    <flux:button wire:click="create" icon="plus" class="mt-5 rounded-2xl">
                                        {{ __('Tambah Pengguna') }}
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="divide-y divide-zinc-100 dark:divide-zinc-800 lg:hidden">
            @forelse ($users as $user)
                @php
                    $roleName = $user->roles->first()?->name ?? '-';
                @endphp

                <div class="p-5">
                    <div class="flex items-start gap-4">
                        <div
                            class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-violet-50 text-sm font-bold text-violet-700 dark:bg-violet-500/10 dark:text-violet-300">
                            {{ $user->initials() }}
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">
                                    {{ $user->name }}
                                </h3>

                                @if ($user->is_active)
                                    <span
                                        class="inline-flex items-center rounded-xl bg-emerald-50 px-2 py-0.5 text-[11px] font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-500/20">
                                        {{ __('Aktif') }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center rounded-xl bg-rose-50 px-2 py-0.5 text-[11px] font-semibold text-rose-700 ring-1 ring-inset ring-rose-200 dark:bg-rose-500/10 dark:text-rose-300 dark:ring-rose-500/20">
                                        {{ __('Nonaktif') }}
                                    </span>
                                @endif
                            </div>

                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $user->email }}
                            </p>

                            <div class="mt-3 flex flex-wrap gap-2">
                                <span
                                    class="inline-flex items-center rounded-xl px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $roleBadge($roleName) }}">
                                    {{ $roleName }}
                                </span>

                                <span
                                    class="inline-flex items-center rounded-xl bg-zinc-50 px-2.5 py-1 text-xs font-medium text-zinc-600 ring-1 ring-inset ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-700">
                                    {{ $user->department?->name ?? __('Tanpa Departemen') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <flux:button wire:click="edit({{ $user->id }})" icon="pencil-square" variant="filled"
                            size="sm" class="w-full justify-center rounded-xl text-blue-600 dark:text-blue-400">
                            {{ __('Edit') }}
                        </flux:button>

                        @if (Auth::id() !== $user->id)
                            <flux:button wire:click="confirmDelete({{ $user->id }})" icon="trash"
                                variant="danger" size="sm" class="w-full justify-center rounded-xl">
                                {{ __('Hapus') }}
                            </flux:button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="flex min-h-[320px] flex-col items-center justify-center px-6 py-12 text-center">
                    <h3 class="text-xl font-semibold text-zinc-900 dark:text-white">
                        {{ __('Belum ada pengguna') }}
                    </h3>
                    <p class="mt-2 max-w-md text-sm leading-6 text-zinc-500 dark:text-zinc-400">
                        {{ __('Tambahkan pengguna pertama agar sistem bisa digunakan oleh tim.') }}
                    </p>

                    <flux:button wire:click="create" icon="plus" class="mt-5 rounded-2xl">
                        {{ __('Tambah Pengguna') }}
                    </flux:button>
                </div>
            @endforelse
        </div>
        {{-- Pagination --}}
        @if ($users->hasPages())
            <div class="border-t border-zinc-100 px-5 py-4 dark:border-zinc-800">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
