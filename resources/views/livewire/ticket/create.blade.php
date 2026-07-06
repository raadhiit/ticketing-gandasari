@php
    $priorityGuides = [
        [
            'label' => 'Low',
            'badge' => 'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-500/20',
            'description' => 'Masalah minor, tidak mengganggu pekerjaan.',
        ],
        [
            'label' => 'Medium',
            'badge' => 'bg-amber-50 text-amber-700 ring-amber-200 dark:bg-amber-500/10 dark:text-amber-300 dark:ring-amber-500/20',
            'description' => 'Mengganggu sebagian pekerjaan.',
        ],
        [
            'label' => 'High',
            'badge' => 'bg-orange-50 text-orange-700 ring-orange-200 dark:bg-orange-500/10 dark:text-orange-300 dark:ring-orange-500/20',
            'description' => 'Menghambat pekerjaan penting.',
        ],
        [
            'label' => 'Urgent',
            'badge' => 'bg-rose-50 text-rose-700 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-300 dark:ring-rose-500/20',
            'description' => 'Sistem down / berdampak besar.',
        ],
    ];
@endphp

<div class="mx-auto max-w-7xl space-y-6">
    <x-confirm-dialog name="confirm-create" />

    {{-- Header --}}
    <div class="space-y-3">
        <flux:button
            :href="route('tickets.index')"
            wire:navigate
            icon="arrow-left"
            variant="ghost"
            class="-ml-2 w-fit rounded-2xl"
        >
            {{ __('Kembali') }}
        </flux:button>

        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white md:text-3xl">
                {{ __('Buat Ticket Baru') }}
            </h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400 md:text-base">
                {{ __('Laporkan masalah atau ajukan permintaan bantuan.') }}
            </p>
        </div>
    </div>

    <form class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        {{-- Main Form --}}
        <div class="xl:col-span-2">
            <div class="overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex items-start gap-4 border-b border-zinc-100 px-5 py-5 dark:border-zinc-800">
                    <div class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-500 text-white shadow-lg shadow-emerald-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-8.25a2.25 2.25 0 0 0-2.25-2.25H6.75A2.25 2.25 0 0 0 4.5 6v12A2.25 2.25 0 0 0 6.75 20.25h6.75" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25h6M9 12h6m-6 3.75h3" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 21l3-3m0 0-3-3m3 3h-9" />
                        </svg>
                    </div>

                    <div>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">
                            {{ __('Detail Ticket') }}
                        </h2>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                            {{ __('Isi informasi berikut dengan lengkap agar tim dapat membantu Anda lebih cepat.') }}
                        </p>
                    </div>
                </div>

                <div class="space-y-5 p-5">
                    {{-- Judul --}}
                    <flux:field>
                        <flux:label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            {{ __('Judul') }}
                            <span class="ml-0.5 text-red-500 dark:text-red-400">*</span>
                        </flux:label>

                        <flux:input
                            wire:model="title"
                            placeholder="{{ __('Contoh: Tidak bisa login ERP sejak pagi') }}"
                            class="h-12 rounded-2xl dark:bg-zinc-950 dark:border-zinc-700/50 dark:text-white dark:placeholder-zinc-500"
                        />

                        <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                            {{ __('Gunakan judul singkat dan langsung ke inti masalah.') }}
                        </p>

                        <flux:error name="title" />
                    </flux:field>

                    {{-- Deskripsi --}}
                    <flux:field>
                        <flux:label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            {{ __('Deskripsi') }}
                            <span class="ml-0.5 text-red-500 dark:text-red-400">*</span>
                        </flux:label>

                        <div class="space-y-2">
                            <x-trix-editor
                                id="ticket-description-editor"
                                wire:model="description"
                                placeholder="{{ __('Jelaskan masalah secara detail...') }}"
                            />

                        </div>

                        <flux:error name="description" />
                    </flux:field>

                    {{-- Kategori + Prioritas --}}
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <flux:field>
                            <flux:label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                {{ __('Kategori') }}
                                <span class="ml-0.5 text-red-500 dark:text-red-400">*</span>
                            </flux:label>

                            <flux:select
                                wire:model="category_id"
                                class="h-12 rounded-2xl dark:bg-zinc-950 dark:border-zinc-700/50 dark:text-white"
                            >
                                <option value="">{{ __('Pilih kategori') }}</option>
                                @foreach ($categories as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </flux:select>

                            <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                                {{ __('Pilih kategori yang paling sesuai agar ticket langsung mengarah ke tim yang tepat.') }}
                            </p>

                            <flux:error name="category_id" />
                        </flux:field>

                        <flux:field>
                            <flux:label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                {{ __('Prioritas') }}
                                <span class="ml-0.5 text-red-500 dark:text-red-400">*</span>
                            </flux:label>

                            <flux:select
                                wire:model="priority"
                                class="h-12 rounded-2xl dark:bg-zinc-950 dark:border-zinc-700/50 dark:text-white"
                            >
                                <option value="LOW">Low</option>
                                <option value="MEDIUM">Medium</option>
                                <option value="HIGH">High</option>
                                <option value="URGENT">Urgent</option>
                            </flux:select>

                            <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                                {{ __('Pilih prioritas berdasarkan dampak masalah, bukan berdasarkan urgensi pribadi.') }}
                            </p>

                            <flux:error name="priority" />
                        </flux:field>
                    </div>

                    {{-- Departemen + Nama Pembuat --}}
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <flux:field>
                            <flux:label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                {{ __('Departemen') }}
                            </flux:label>

                            <flux:select
                                wire:model="department_id"
                                class="h-12 rounded-2xl dark:bg-zinc-950 dark:border-zinc-700/50 dark:text-white"
                            >
                                <option value="">{{ __('Pilih departemen') }}</option>
                                @foreach ($departments as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </flux:select>

                            <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                                {{ __('Gunakan departemen yang terkait dengan pelapor atau kebutuhan ticket.') }}
                            </p>

                            <flux:error name="department_id" />
                        </flux:field>

                        <flux:field>
                            <flux:label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                {{ __('Nama Pembuat') }}
                                <span class="ml-0.5 text-red-500 dark:text-red-400">*</span>
                            </flux:label>

                            <flux:input
                                wire:model="requester_name"
                                placeholder="{{ __('Nama lengkap') }}"
                                class="h-12 rounded-2xl dark:bg-zinc-950 dark:border-zinc-700/50 dark:text-white dark:placeholder-zinc-500"
                            />

                            <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                                {{ __('Secara default diisi dari akun login. Bisa diubah jika membuat ticket atas nama orang lain.') }}
                            </p>

                            <flux:error name="requester_name" />
                        </flux:field>
                    </div>

                    {{-- Lampiran --}}
                    <flux:field>
                        <flux:label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            {{ __('Lampiran') }}
                            <span class="text-zinc-400 dark:text-zinc-500">({{ __('Opsional') }})</span>
                        </flux:label>

                        <input id="ticket-attachments" type="file" wire:model="attachments" multiple class="sr-only" />

                        <label
                            for="ticket-attachments"
                            class="flex cursor-pointer flex-col items-center justify-center rounded-2xl border border-dashed border-zinc-300 bg-zinc-50 px-6 py-8 text-center transition hover:border-emerald-400 hover:bg-emerald-50/40 dark:border-zinc-700 dark:bg-zinc-950/40 dark:hover:border-emerald-500/40 dark:hover:bg-emerald-500/5"
                        >
                            <div class="flex size-14 items-center justify-center rounded-2xl bg-white text-emerald-600 shadow-sm dark:bg-zinc-900 dark:text-emerald-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V7.5m0 0-3 3m3-3 3 3" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 18A4.5 4.5 0 0 1 3 13.5a4.5 4.5 0 0 1 4.09-4.482A5.25 5.25 0 0 1 17.25 8.25h.75A3.75 3.75 0 0 1 21.75 12a3.75 3.75 0 0 1-3.75 3.75H12" />
                                </svg>
                            </div>

                            <p class="mt-4 text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                {{ __('Klik untuk memilih file atau seret file ke area ini') }}
                            </p>
                            <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                                {{ __('Format: PNG, JPG, PDF, DOC, DOCX, XLS, XLSX, ZIP, RAR, TXT, CSV. Maks. 10 MB per file.') }}
                            </p>
                        </label>

                        <flux:error name="attachments.*" />

                        <div wire:loading wire:target="attachments" class="mt-3 text-sm text-zinc-500 dark:text-zinc-400">
                            {{ __('Mengupload...') }}
                        </div>

                        @if ($attachments)
                            <div class="mt-4 rounded-2xl border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-800 dark:bg-zinc-950/50">
                                <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                    {{ __('File terpilih') }}
                                </p>

                                <ul class="mt-3 space-y-2">
                                    @foreach ($attachments as $index => $file)
                                        <li class="flex items-center justify-between gap-3 rounded-xl bg-white px-3 py-2 text-sm text-zinc-700 ring-1 ring-inset ring-zinc-200 dark:bg-zinc-900 dark:text-zinc-300 dark:ring-zinc-700">
                                            <div class="min-w-0">
                                                <p class="truncate">{{ $file->getClientOriginalName() }}</p>
                                                <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                                    {{ number_format($file->getSize() / 1024, 0) }} KB
                                                </p>
                                            </div>

                                            <button
                                                type="button"
                                                wire:click="removeAttachment({{ $index }})"
                                                class="shrink-0 rounded-lg px-2 py-1 text-xs font-medium text-red-600 transition hover:bg-red-50 hover:text-red-700 dark:text-red-400 dark:hover:bg-red-500/10"
                                            >
                                                {{ __('Hapus') }}
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </flux:field>
                </div>

                {{-- Footer Action --}}
                <div class="flex flex-col gap-4 border-t border-zinc-100 px-5 py-5 dark:border-zinc-800 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-start gap-3 text-sm text-zinc-500 dark:text-zinc-400">
                        <div class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5h.008v.008H12V16.5Zm0-9v6" />
                            </svg>
                        </div>
                        <p>
                            {{ __('Setelah dikirim, ticket akan masuk ke daftar ticket dan dapat dipantau statusnya.') }}
                        </p>
                    </div>

                    <div class="flex flex-col-reverse gap-3 sm:flex-row">
                        <flux:button
                            :href="route('tickets.index')"
                            wire:navigate
                            variant="ghost"
                            class="rounded-2xl"
                        >
                            {{ __('Batal') }}
                        </flux:button>

                        <flux:button
                            type="button"
                            variant="primary"
                            wire:target="save,attachments"
                            wire:loading.attr="disabled"
                            class="rounded-2xl px-5"
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
                            "
                        >
                            <span wire:loading.remove wire:target="save">
                                {{ __('Kirim Ticket') }}
                            </span>

                            <span wire:loading wire:target="save">
                                {{ __('Mengirim...') }}
                            </span>
                        </flux:button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Panduan Singkat --}}
            <div class="rounded-3xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex items-start gap-4">
                    <div class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-500 text-white shadow-lg shadow-emerald-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75 8.967 8.967 0 0 0 3 4.254v13.5A8.967 8.967 0 0 1 6 17.25a8.967 8.967 0 0 1 6 2.292m0-13.5A8.967 8.967 0 0 1 18 3.75c1.062 0 2.08.185 3 .504v13.5a8.967 8.967 0 0 0-3-.504 8.967 8.967 0 0 0-6 2.292m0-13.5v13.5" />
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                            {{ __('Panduan Singkat') }}
                        </h3>
                    </div>
                </div>

                <ul class="mt-5 space-y-4">
                    @foreach ([
                        'Gunakan judul singkat dan jelas.',
                        'Jelaskan kronologi masalah.',
                        'Sertakan pesan error jika ada.',
                        'Pilih prioritas sesuai dampak masalah.',
                    ] as $tip)
                        <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                            <div class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7" />
                                </svg>
                            </div>
                            <span>{{ __($tip) }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Setelah Ticket Dikirim --}}
            <div class="rounded-3xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex items-start gap-4">
                    <div class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-600/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.804A.75.75 0 0 1 4.22 2.85l15.294 8.422a.75.75 0 0 1 0 1.312L4.22 21.006a.75.75 0 0 1-.951-.954L6 12Zm0 0h7.5" />
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                            {{ __('Setelah Ticket Dikirim') }}
                        </h3>
                    </div>
                </div>

                <div class="mt-5 space-y-5">
                    @foreach ([
                        ['no' => 1, 'title' => 'Ticket masuk ke antrian tim', 'desc' => 'Tim akan meninjau dan menindaklanjuti ticket Anda.'],
                        ['no' => 2, 'title' => 'Anda menerima update', 'desc' => 'Jika ada perkembangan atau butuh info tambahan, status ticket akan diperbarui.'],
                        ['no' => 3, 'title' => 'Pantau status ticket', 'desc' => 'Cek perkembangan ticket di halaman daftar ticket.'],
                    ] as $step)
                        <div class="flex items-start gap-3">
                            <div class="flex size-7 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-xs font-bold text-white">
                                {{ $step['no'] }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-zinc-800 dark:text-zinc-200">
                                    {{ __($step['title']) }}
                                </p>
                                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ __($step['desc']) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Panduan Prioritas --}}
            <div class="rounded-3xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex items-start gap-4">
                    <div class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-amber-500 text-white shadow-lg shadow-amber-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18m0-12h12.75l-1.5 3 1.5 3H3" />
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                            {{ __('Panduan Prioritas') }}
                        </h3>
                    </div>
                </div>

                <div class="mt-5 space-y-3">
                    @foreach ($priorityGuides as $guide)
                        <div class="flex items-start gap-3">
                            <span class="inline-flex shrink-0 items-center rounded-xl px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $guide['badge'] }}">
                                {{ $guide['label'] }}
                            </span>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                                {{ __($guide['description']) }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </form>
</div>