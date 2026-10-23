<div>
    <form class="flex max-w-[1100px] flex-col gap-3" onsubmit="return false">
        <x-panel :title="__('Edit Ticket')">
            <div class="kv kv-form">
                <div class="kv-k kv-first"><label for="judul">{{ __('Judul') }}</label><span class="text-required" aria-hidden="true">*</span></div>
                <div class="kv-v kv-first kv-span3">
                    <input id="judul" type="text" wire:model="title" class="inp" required
                        placeholder="{{ __('Contoh: Email perusahaan tidak bisa dikirim') }}">
                    <p class="hint">{{ $ticket->ticket_number }} — {{ __('Perbarui detail ticket') }}</p>
                    @error('title')<p class="hint !text-danger">{{ $message }}</p>@enderror
                </div>

                <div class="kv-k"><label for="kat">{{ __('Kategori') }}</label></div>
                <div class="kv-v">
                    <select id="kat" wire:model="category_id" class="sel">
                        <option value="">{{ __('Pilih kategori') }}</option>
                        @foreach ($categories as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
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
                    @error('priority')<p class="hint !text-danger">{{ $message }}</p>@enderror
                </div>

                <div class="kv-k"><label for="dep">{{ __('Departemen') }}</label></div>
                <div class="kv-v kv-span3">
                    <select id="dep" wire:model="department_id" class="sel">
                        <option value="">{{ __('Pilih departemen') }}</option>
                        @foreach ($departments as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('department_id')<p class="hint !text-danger">{{ $message }}</p>@enderror
                </div>
            </div>
        </x-panel>

        <section class="panel" aria-label="{{ __('Deskripsi') }}">
            <div class="panel-strip">
                <div class="panel-tab">{{ __('Deskripsi') }}<span class="ml-1 text-[#ff9aa8]" aria-hidden="true">*</span></div>
            </div>

            <div class="editor-flat">
                <x-trix-editor id="ticket-edit-description-editor" wire:model="description"
                    placeholder="{{ __('Jelaskan masalah secara detail...') }}" />
            </div>

            @error('description')<p class="m-0 px-4 pb-3 text-[13px] text-danger">{{ $message }}</p>@enderror
        </section>

        <div class="flex justify-end gap-2.5 rounded-lg bg-white px-4 py-3.5">
            <a href="{{ route('tickets.show', $ticket) }}" wire:navigate class="btn btn-n">{{ __('Batal') }}</a>
            <button type="button" class="btn btn-p" wire:click="confirmSave" wire:loading.attr="disabled">{{ __('Simpan Perubahan') }}</button>
        </div>
    </form>

    <x-confirm-dialog name="confirm-edit" />
</div>
