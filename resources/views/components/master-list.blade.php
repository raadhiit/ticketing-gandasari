@props([
    'items',
    'total',
    'singular',
    'showForm' => false,
    'editId' => null,
    'placeholderName' => '',
    'placeholderDescription' => '',
])

<div class="flex flex-col gap-3">
    <div class="tbar" role="toolbar" aria-label="{{ __('Aksi') }}">
        <button type="button" class="tbar-btn" wire:click="create">+ {{ __('Tambah') }} {{ $singular }}</button>
    </div>

    @if ($showForm)
        <x-panel :title="$editId ? __('Edit :name', ['name' => $singular]) : __('Tambah :name Baru', ['name' => $singular])">
            <form wire:submit="save">
                <div class="kv kv-form">
                    <div class="kv-k kv-first"><label for="master-name">{{ __('Nama') }} {{ $singular }}</label><span class="text-required" aria-hidden="true">*</span></div>
                    <div class="kv-v kv-first">
                        <input id="master-name" type="text" wire:model="name" class="inp" placeholder="{{ $placeholderName }}">
                        @error('name')<p class="hint !text-danger">{{ $message }}</p>@enderror
                    </div>
                    <div class="kv-k kv-first"><label for="master-description">{{ __('Deskripsi') }}</label></div>
                    <div class="kv-v kv-first">
                        <input id="master-description" type="text" wire:model="description" class="inp" placeholder="{{ $placeholderDescription }}">
                        @error('description')<p class="hint !text-danger">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex justify-end gap-2.5 border-t border-line px-4 py-3.5">
                    <button type="button" class="btn btn-n" wire:click="cancel">{{ __('Batal') }}</button>
                    <button type="submit" class="btn btn-p">{{ $editId ? __('Simpan Perubahan') : __('Simpan :name', ['name' => $singular]) }}</button>
                </div>
            </form>
        </x-panel>
    @endif

    <x-panel :title="__('Daftar :name', ['name' => $singular])" :count="$total">
        <div class="overflow-x-auto">
            <table class="tbl min-w-[560px]">
                <thead>
                    <tr>
                        <th>{{ $singular }}</th>
                        <th>{{ __('Deskripsi') }}</th>
                        <th class="text-right">{{ __('Aksi') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($items as $item)
                        <tr wire:key="master-{{ $item->id }}">
                            <td class="font-bold text-ink-strong">{{ $item->name }}</td>
                            <td class="text-muted">{{ $item->description ?: __('Tidak ada deskripsi') }}</td>
                            <td class="text-right whitespace-nowrap">
                                <button type="button" class="act" wire:click="edit({{ $item->id }})">{{ __('Edit') }}</button>
                                <button type="button" class="act is-danger ml-3.5" wire:click="confirmDelete({{ $item->id }})">{{ __('Hapus') }}</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-muted">{{ __('Belum ada data.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $items->links() }}
    </x-panel>

    <x-confirm-dialog name="confirm-delete" />
</div>
