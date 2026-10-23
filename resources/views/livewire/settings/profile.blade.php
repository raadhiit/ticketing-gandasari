<x-settings.layout>
    <x-panel :title="__('Profil')">
        <form wire:submit="updateProfileInformation">
            <div class="kv kv-form">
                <div class="kv-k kv-first"><label for="p-name">{{ __('Nama') }}</label><span class="text-required" aria-hidden="true">*</span></div>
                <div class="kv-v kv-first">
                    <input id="p-name" type="text" wire:model="name" class="inp" required autofocus autocomplete="name">
                    @error('name')<p class="hint !text-danger">{{ $message }}</p>@enderror
                </div>
                <div class="kv-k kv-first"><label for="p-email">{{ __('Email') }}</label><span class="text-required" aria-hidden="true">*</span></div>
                <div class="kv-v kv-first">
                    <input id="p-email" type="email" wire:model="email" class="inp" required autocomplete="email">
                    @error('email')<p class="hint !text-danger">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end border-t border-line px-4 py-3.5">
                <button type="submit" class="btn btn-p">{{ __('Simpan') }}</button>
            </div>
        </form>
    </x-panel>

    <livewire:settings.delete-user-form />
</x-settings.layout>
