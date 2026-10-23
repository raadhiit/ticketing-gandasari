@php
    $isSupport = auth()->user()->can('ticket.assign');
@endphp

<div>
    <div class="flex flex-wrap items-start gap-4">
        <form class="flex min-w-0 flex-[999_1_560px] flex-col gap-3" onsubmit="return false">
            <x-panel :title="__('Detail Ticket')">
                <div class="kv kv-form">
                    <div class="kv-k kv-first"><label for="judul">{{ __('Judul') }}</label><span class="text-required" aria-hidden="true">*</span></div>
                    <div class="kv-v kv-first kv-span3">
                        <input id="judul" type="text" wire:model="title" class="inp" required
                            placeholder="{{ __('Contoh: Tidak bisa login ERP sejak pagi') }}">
                        <p class="hint">{{ __('Gunakan judul singkat dan langsung ke inti masalah.') }}</p>
                        @error('title')<p class="hint !text-danger">{{ $message }}</p>@enderror
                    </div>

                    <div class="kv-k"><label for="kat">{{ __('Kategori') }}</label><span class="text-required" aria-hidden="true">*</span></div>
                    <div class="kv-v">
                        <select id="kat" wire:model="category_id" class="sel" required>
                            <option value="">{{ __('Pilih kategori') }}</option>
                            @foreach ($categories as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <p class="hint">{{ __('Pilih yang paling sesuai agar ticket langsung ke tim yang tepat.') }}</p>
                        @error('category_id')<p class="hint !text-danger">{{ $message }}</p>@enderror
                    </div>
                    <div class="kv-k"><label for="pri">{{ __('Prioritas') }}</label><span class="text-required" aria-hidden="true">*</span></div>
                    <div class="kv-v">
                        <select id="pri" wire:model="priority" class="sel">
                            <option value="LOW">Low</option>
                            <option value="MEDIUM">Medium</option>
                            <option value="HIGH">High</option>
                            <option value="URGENT">Urgent</option>
                        </select>
                        <p class="hint">{{ __('Berdasarkan dampak masalah, bukan urgensi pribadi. Lihat panduan di kanan.') }}</p>
                        @error('priority')<p class="hint !text-danger">{{ $message }}</p>@enderror
                    </div>

                    <div class="kv-k"><label for="dep">{{ __('Departemen') }}</label></div>
                    <div class="kv-v">
                        <select id="dep" wire:model="department_id" class="sel">
                            <option value="">{{ __('Pilih departemen') }}</option>
                            @foreach ($departments as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <p class="hint">{{ __('Departemen yang terkait dengan pelapor atau kebutuhan ticket.') }}</p>
                        @error('department_id')<p class="hint !text-danger">{{ $message }}</p>@enderror
                    </div>
                    <div class="kv-k"><label for="nama">{{ __('Pelapor') }}</label></div>
                    <div class="kv-v">
                        <input id="nama" type="text" wire:model="requester_name" class="inp" @unless ($isSupport) readonly @endunless>
                        <p class="hint">{{ $isSupport ? __('Diisi dari akun Anda. Bisa diubah jika membuat ticket atas nama orang lain.') : __('Diisi otomatis dari akun Anda.') }}</p>
                        @error('requester_name')<p class="hint !text-danger">{{ $message }}</p>@enderror
                    </div>
                </div>
            </x-panel>

            <section class="panel" aria-label="{{ __('Deskripsi') }}">
                <div class="panel-strip">
                    <div class="panel-tab">{{ __('Deskripsi') }}<span class="ml-1 text-[#ff9aa8]" aria-hidden="true">*</span></div>
                </div>

                <div class="editor-flat">
                    <x-trix-editor id="ticket-description-editor" wire:model="description"
                        placeholder="{{ __('Jelaskan masalah secara detail: apa yang terjadi, sejak kapan, dan langkah yang sudah dicoba.') }}" />
                </div>

                @error('description')<p class="m-0 px-4 pb-3 text-[13px] text-danger">{{ $message }}</p>@enderror
            </section>

            <section class="panel" aria-label="{{ __('Lampiran') }}">
                <div class="panel-strip">
                    <div class="panel-tab">{{ __('Lampiran') }} <span class="ml-1.5 font-normal text-[#c7cfdc]">({{ __('opsional') }})</span></div>
                </div>

                <input id="ticket-attachments" type="file" wire:model="attachments" multiple class="sr-only">

                <div class="att">
                    <div class="att-h">{{ __('Nama file') }}</div>
                    <div class="att-h">{{ __('Ukuran') }}</div>

                    @forelse ($attachments as $index => $file)
                        <div class="att-c flex items-center justify-between gap-3 !text-ink">
                            <span class="min-w-0 truncate">{{ $file->getClientOriginalName() }}</span>
                            <button type="button" wire:click="removeAttachment({{ $index }})"
                                class="shrink-0 cursor-pointer border-0 bg-transparent p-0 text-[13px] font-semibold text-danger hover:text-danger-hover">{{ __('Hapus') }}</button>
                        </div>
                        <div class="att-c">{{ number_format($file->getSize() / 1024, 0) }} KB</div>
                    @empty
                        <div class="att-c">{{ __('Belum ada lampiran.') }}</div>
                        <div class="att-c"></div>
                    @endforelse
                </div>

                <div class="px-3 py-2.5 text-sm font-semibold">
                    <label for="ticket-attachments" class="cursor-pointer text-link hover:text-link-hover">+ {{ __('Tambah lampiran') }}</label>
                    <span wire:loading wire:target="attachments" class="ml-3 font-normal text-muted">{{ __('Mengupload...') }}</span>
                </div>

                @error('attachments.*')<p class="m-0 px-3 pb-2 text-[13px] text-danger">{{ $message }}</p>@enderror

                <p class="hint m-0 px-3 pt-0 pb-3.5">{{ __('PNG, JPG, PDF, DOC, DOCX, XLS, XLSX, ZIP, RAR, TXT, CSV. Maksimal 10 MB per file.') }}</p>
            </section>

            <div class="flex justify-end gap-2.5 rounded-lg bg-white px-4 py-3.5">
                <a href="{{ route('tickets.index') }}" wire:navigate class="btn btn-n">{{ __('Batal') }}</a>

                <button type="button" class="btn btn-p" wire:target="save,attachments" wire:loading.attr="disabled"
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
                    ">
                    <span wire:loading.remove wire:target="save">{{ __('Kirim Ticket') }}</span>
                    <span wire:loading wire:target="save">{{ __('Mengirim...') }}</span>
                </button>
            </div>
        </form>

        <aside class="max-w-[340px] flex-[1_1_300px] self-start min-[701px]:sticky min-[701px]:top-4" aria-label="{{ __('Panduan') }}">
            <div class="guide">
                <h2>{{ __('Panduan') }}</h2>

                <section>
                    <h3>{{ __('Menulis ticket') }}</h3>
                    <ul>
                        <li>{{ __('Judul singkat dan jelas.') }}</li>
                        <li>{{ __('Jelaskan kronologi masalah.') }}</li>
                        <li>{{ __('Sertakan pesan error jika ada.') }}</li>
                        <li>{{ __('Pilih prioritas sesuai dampak masalah.') }}</li>
                    </ul>
                </section>

                <section>
                    <h3>{{ __('Prioritas') }}</h3>
                    <table class="w-full border-collapse text-[13.5px]">
                        <tbody>
                            @foreach ([
                                'low' => ['Low', 'Masalah minor, tidak mengganggu pekerjaan.'],
                                'medium' => ['Medium', 'Mengganggu sebagian pekerjaan.'],
                                'high' => ['High', 'Menghambat pekerjaan penting.'],
                                'urgent' => ['Urgent', 'Sistem down atau berdampak besar.'],
                            ] as $key => [$label, $description])
                                <tr>
                                    <td class="py-1.5 pr-3.5 align-top leading-[1.4] font-semibold whitespace-nowrap"><span class="dot dot-{{ $key }} !mr-2"></span>{{ $label }}</td>
                                    <td class="py-1.5 align-top leading-[1.4]">{{ __($description) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>

                <section>
                    <h3>{{ __('Setelah ticket dikirim') }}</h3>
                    <ol>
                        <li>{{ __('Ticket masuk ke antrian tim dan akan ditinjau.') }}</li>
                        <li>{{ __('Anda menerima update jika ada perkembangan atau butuh info tambahan.') }}</li>
                        <li>{{ __('Pantau status di halaman Tickets.') }}</li>
                    </ol>
                </section>
            </div>
        </aside>
    </div>

    <x-confirm-dialog name="confirm-create" />
</div>
