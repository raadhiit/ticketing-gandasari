<div>
    <x-panel :title="__('Hapus Akun')">
        <div class="flex flex-wrap items-center justify-between gap-3 px-4 py-3.5">
            <p class="m-0 text-sm text-ink">{{ __('Hapus akun Anda beserta seluruh datanya.') }}</p>

            <flux:modal.trigger name="confirm-user-deletion">
                <button type="button" class="btn btn-d">{{ __('Hapus Akun') }}</button>
            </flux:modal.trigger>
        </div>
    </x-panel>

    <flux:modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable class="w-full max-w-lg">
        <form wire:submit="deleteUser" class="flex flex-col gap-4">
            <div>
                <h3 class="m-0 text-lg font-bold text-ink-strong">{{ __('Yakin ingin menghapus akun Anda?') }}</h3>
                <p class="mt-1 mb-0 text-sm text-muted">
                    {{ __('Setelah akun dihapus, seluruh data akan dihapus permanen. Masukkan password Anda untuk mengonfirmasi.') }}
                </p>
            </div>

            <div>
                <label for="d-pass" class="mb-1.5 block text-[11px] font-bold tracking-[.03em] text-ink-label uppercase">{{ __('Password') }}</label>
                <input id="d-pass" type="password" wire:model="password" class="inp w-full">
                @error('password')<p class="hint !text-danger">{{ $message }}</p>@enderror
            </div>

            <div class="flex justify-end gap-2.5">
                <button type="button" class="btn btn-n" x-on:click="$flux.modal('confirm-user-deletion').close()">{{ __('Batal') }}</button>
                <button type="submit" class="btn btn-d">{{ __('Hapus Akun') }}</button>
            </div>
        </form>
    </flux:modal>
</div>
