<x-layouts::auth :title="__('Konfirmasi Password')">
    <x-auth-header :title="__('Konfirmasi Password')" :description="__('Ini adalah area aman. Konfirmasi password Anda sebelum melanjutkan.')" />

    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-4">
        @csrf

        <div>
            <label for="password" class="mb-1.5 block text-[11px] font-bold tracking-[.03em] text-ink-label uppercase">{{ __('Password') }}</label>
            <input id="password" name="password" type="password" class="inp h-9 w-full" required autofocus autocomplete="current-password">
            @error('password')<p class="hint !text-danger">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="btn btn-p w-full" data-test="confirm-password-button">{{ __('Konfirmasi') }}</button>
    </form>
</x-layouts::auth>
