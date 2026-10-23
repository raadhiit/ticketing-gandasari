<x-settings.layout>
    <x-panel :title="__('Update password')">
        <form wire:submit="updatePassword">
            <div class="kv kv-form">
                <div class="kv-k kv-first"><label for="s-current">{{ __('Password saat ini') }}</label><span class="text-required" aria-hidden="true">*</span></div>
                <div class="kv-v kv-first kv-span3">
                    <input id="s-current" type="password" wire:model="current_password" class="inp" required autocomplete="current-password">
                    @error('current_password')<p class="hint !text-danger">{{ $message }}</p>@enderror
                </div>

                <div class="kv-k"><label for="s-new">{{ __('Password baru') }}</label><span class="text-required" aria-hidden="true">*</span></div>
                <div class="kv-v">
                    <input id="s-new" type="password" wire:model="password" class="inp" required autocomplete="new-password"
                        passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}">
                    <p class="hint">{{ __('Gunakan password yang panjang dan acak agar akun tetap aman.') }}</p>
                    @error('password')<p class="hint !text-danger">{{ $message }}</p>@enderror
                </div>
                <div class="kv-k"><label for="s-confirm">{{ __('Konfirmasi password') }}</label><span class="text-required" aria-hidden="true">*</span></div>
                <div class="kv-v">
                    <input id="s-confirm" type="password" wire:model="password_confirmation" class="inp" required autocomplete="new-password"
                        passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}">
                </div>
            </div>

            <div class="flex justify-end border-t border-line px-4 py-3.5">
                <button type="submit" class="btn btn-p" data-test="update-password-button">{{ __('Simpan') }}</button>
            </div>
        </form>
    </x-panel>
</x-settings.layout>
