<div class="space-y-6">
    <x-confirm-dialog name="confirm-delete" />

    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white md:text-3xl">
                {{ __('Kategori Ticket') }}
            </h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400 md:text-base">
                {{ __('Kelola kategori ticket agar pelaporan issue lebih terstruktur dan mudah dipahami user') }}
            </p>
        </div>

        <flux:button
            wire:click="create"
            icon="plus"
            class="h-11 rounded-2xl px-5 shadow-sm"
        >
            {{ __('Tambah Kategori') }}
        </flux:button>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div class="rounded-3xl border border-emerald-200/70 bg-white p-5 shadow-sm dark:border-emerald-500/20 dark:bg-zinc-900">
            <div class="flex items-start justify-between gap-4">
                <div class="flex size-14 items-center justify-center rounded-2xl bg-emerald-500 text-white shadow-lg shadow-emerald-500/25">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 3.75h9A2.25 2.25 0 0 1 18.75 6v12A2.25 2.25 0 0 1 16.5 20.25h-9A2.25 2.25 0 0 1 5.25 18V6A2.25 2.25 0 0 1 7.5 3.75Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25h6m-6 4.5h6m-6 4.5h3" />
                    </svg>
                </div>

                <div class="text-right">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-600 dark:text-emerald-400">
                        {{ __('Total Kategori') }}
                    </p>
                    <p class="mt-1 text-4xl font-bold leading-none text-zinc-900 dark:text-white">
                        {{ $totalCategories }}
                    </p>
                </div>
            </div>

            <p class="mt-6 text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('Kategori yang tersedia dalam sistem') }}
            </p>
        </div>

        <div class="rounded-3xl border border-blue-200/70 bg-white p-5 shadow-sm dark:border-blue-500/20 dark:bg-zinc-900">
            <div class="flex items-start justify-between gap-4">
                <div class="flex size-14 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-600/25">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>

                <div class="text-right">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-600 dark:text-blue-400">
                        {{ __('Status') }}
                    </p>
                    <p class="mt-1 text-3xl font-bold leading-none text-zinc-900 dark:text-white">
                        {{ __('Aktif') }}
                    </p>
                </div>
            </div>

            <p class="mt-6 text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('Kategori siap dipakai saat pembuatan ticket') }}
            </p>
        </div>
    </div>

    {{-- Form --}}
    @if ($showForm)
        <div class="overflow-hidden rounded-3xl border border-emerald-200 bg-white shadow-sm dark:border-emerald-500/20 dark:bg-zinc-900">
            <div class="flex items-start gap-4 border-b border-zinc-100 px-5 py-5 dark:border-zinc-800">
                <div class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-500 text-white shadow-lg shadow-emerald-500/20">
                    @if ($editId)
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    @endif
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">
                        {{ $editId ? __('Edit Kategori') : __('Tambah Kategori Baru') }}
                    </h2>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                        {{ $editId ? __('Perbarui nama atau deskripsi kategori yang sudah ada') : __('Buat kategori baru agar user lebih mudah memilih jenis kendala saat membuat ticket') }}
                    </p>
                </div>
            </div>

            <form wire:submit="save" class="space-y-5 p-5">
                <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                    <flux:field>
                        <flux:label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            {{ __('Nama Kategori') }}
                        </flux:label>
                        <flux:input
                            wire:model="name"
                            placeholder="{{ __('Contoh: Hardware') }}"
                            class="h-12 rounded-2xl dark:bg-zinc-950 dark:border-zinc-700/50 dark:text-white dark:placeholder-zinc-500"
                        />
                        <flux:error name="name" />
                    </flux:field>

                    <flux:field>
                        <flux:label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            {{ __('Deskripsi') }}
                        </flux:label>
                        <flux:input
                            wire:model="description"
                            placeholder="{{ __('Contoh: Kendala perangkat fisik dan perlengkapannya') }}"
                            class="h-12 rounded-2xl dark:bg-zinc-950 dark:border-zinc-700/50 dark:text-white dark:placeholder-zinc-500"
                        />
                        <flux:error name="description" />
                    </flux:field>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-zinc-100 pt-5 dark:border-zinc-800 sm:flex-row sm:justify-end">
                    <flux:button
                        type="button"
                        wire:click="cancel"
                        variant="ghost"
                        class="rounded-2xl"
                    >
                        {{ __('Batal') }}
                    </flux:button>

                    <flux:button
                        variant="primary"
                        type="submit"
                        class="rounded-2xl px-5"
                    >
                        {{ $editId ? __('Simpan Perubahan') : __('Simpan Kategori') }}
                    </flux:button>
                </div>
            </form>
        </div>
    @endif

    {{-- Category List --}}
    <div class="overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div class="flex flex-col gap-3 border-b border-zinc-100 px-5 py-5 dark:border-zinc-800 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                    {{ __('Daftar Kategori') }}
                </h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    {{ __('Gunakan nama kategori yang jelas agar user tidak bingung saat membuat ticket') }}
                </p>
            </div>

            <span class="inline-flex w-fit items-center rounded-2xl bg-zinc-50 px-3 py-1.5 text-xs font-semibold text-zinc-600 ring-1 ring-inset ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-700">
                {{ $totalCategories }} {{ __('kategori') }}
            </span>
        </div>

        {{-- Desktop Table --}}
        <div class="hidden overflow-x-auto lg:block">
            <table class="min-w-full">
                <thead class="bg-zinc-50/80 dark:bg-zinc-950/60">
                    <tr class="border-b border-zinc-100 dark:border-zinc-800">
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                            {{ __('Kategori') }}
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                            {{ __('Deskripsi') }}
                        </th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                            {{ __('Aksi') }}
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse ($categories as $cat)
                        <tr class="transition hover:bg-zinc-50 dark:hover:bg-zinc-800/40">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 3.75h9A2.25 2.25 0 0 1 18.75 6v12A2.25 2.25 0 0 1 16.5 20.25h-9A2.25 2.25 0 0 1 5.25 18V6A2.25 2.25 0 0 1 7.5 3.75Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25h6m-6 4.5h6m-6 4.5h3" />
                                        </svg>
                                    </div>

                                    <div>
                                        <p class="text-sm font-semibold text-zinc-900 dark:text-white">
                                            {{ $cat->name }}
                                        </p>
                                        <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">
                                            {{ __('Kategori ticket') }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-4">
                                <p class="max-w-3xl text-sm leading-6 text-zinc-600 dark:text-zinc-400">
                                    {{ $cat->description ?: __('Tidak ada deskripsi') }}
                                </p>
                            </td>

                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <flux:button
                                        wire:click="edit({{ $cat->id }})"
                                        icon="pencil-square"
                                        variant="filled"
                                        size="sm"
                                        class="rounded-xl text-blue-600 dark:text-blue-400"
                                    >
                                        {{ __('Edit') }}
                                    </flux:button>

                                    <flux:button
                                        wire:click="confirmDelete({{ $cat->id }})"
                                        icon="trash"
                                        variant="danger"
                                        size="sm"
                                        class="rounded-xl"
                                    >
                                        {{ __('Hapus') }}
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-16">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <div class="relative mb-5">
                                        <div class="absolute inset-0 rounded-full bg-emerald-400/20 blur-2xl dark:bg-emerald-400/10"></div>
                                        <div class="relative flex size-24 items-center justify-center rounded-[2rem] bg-gradient-to-br from-emerald-100 to-teal-100 text-emerald-600 dark:from-emerald-500/10 dark:to-teal-500/10 dark:text-emerald-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 3.75h9A2.25 2.25 0 0 1 18.75 6v12A2.25 2.25 0 0 1 16.5 20.25h-9A2.25 2.25 0 0 1 5.25 18V6A2.25 2.25 0 0 1 7.5 3.75Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25h6m-6 4.5h6m-6 4.5h3" />
                                            </svg>
                                        </div>
                                    </div>

                                    <h3 class="text-xl font-semibold text-zinc-900 dark:text-white">
                                        {{ __('Belum ada kategori') }}
                                    </h3>
                                    <p class="mt-2 max-w-md text-sm leading-6 text-zinc-500 dark:text-zinc-400">
                                        {{ __('Tambahkan kategori pertama agar ticket bisa dikelompokkan dengan jelas.') }}
                                    </p>

                                    <flux:button wire:click="create" icon="plus" class="mt-5 rounded-2xl">
                                        {{ __('Tambah Kategori') }}
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
            @forelse ($categories as $cat)
                <div class="p-5">
                    <div class="flex items-start gap-4">
                        <div class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 3.75h9A2.25 2.25 0 0 1 18.75 6v12A2.25 2.25 0 0 1 16.5 20.25h-9A2.25 2.25 0 0 1 5.25 18V6A2.25 2.25 0 0 1 7.5 3.75Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25h6m-6 4.5h6m-6 4.5h3" />
                            </svg>
                        </div>

                        <div class="min-w-0 flex-1">
                            <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">
                                {{ $cat->name }}
                            </h3>
                            <p class="mt-1 text-sm leading-6 text-zinc-500 dark:text-zinc-400">
                                {{ $cat->description ?: __('Tidak ada deskripsi') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <flux:button
                            wire:click="edit({{ $cat->id }})"
                            icon="pencil-square"
                            variant="filled"
                            size="sm"
                            class="w-full justify-center rounded-xl text-blue-600 dark:text-blue-400"
                        >
                            {{ __('Edit') }}
                        </flux:button>

                        <flux:button
                            wire:click="confirmDelete({{ $cat->id }})"
                            icon="trash"
                            variant="danger"
                            size="sm"
                            class="w-full justify-center rounded-xl"
                        >
                            {{ __('Hapus') }}
                        </flux:button>
                    </div>
                </div>
            @empty
                <div class="flex min-h-[320px] flex-col items-center justify-center px-6 py-12 text-center">
                    <div class="relative mb-5">
                        <div class="absolute inset-0 rounded-full bg-emerald-400/20 blur-2xl dark:bg-emerald-400/10"></div>
                        <div class="relative flex size-24 items-center justify-center rounded-[2rem] bg-gradient-to-br from-emerald-100 to-teal-100 text-emerald-600 dark:from-emerald-500/10 dark:to-teal-500/10 dark:text-emerald-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 3.75h9A2.25 2.25 0 0 1 18.75 6v12A2.25 2.25 0 0 1 16.5 20.25h-9A2.25 2.25 0 0 1 5.25 18V6A2.25 2.25 0 0 1 7.5 3.75Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25h6m-6 4.5h6m-6 4.5h3" />
                            </svg>
                        </div>
                    </div>

                    <h3 class="text-xl font-semibold text-zinc-900 dark:text-white">
                        {{ __('Belum ada kategori') }}
                    </h3>
                    <p class="mt-2 max-w-md text-sm leading-6 text-zinc-500 dark:text-zinc-400">
                        {{ __('Tambahkan kategori pertama agar ticket bisa dikelompokkan dengan jelas.') }}
                    </p>

                    <flux:button wire:click="create" icon="plus" class="mt-5 rounded-2xl">
                        {{ __('Tambah Kategori') }}
                    </flux:button>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($categories->hasPages())
            <div class="border-t border-zinc-100 px-5 py-4 dark:border-zinc-800">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>