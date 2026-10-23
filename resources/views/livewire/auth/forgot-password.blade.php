<x-layouts::auth :title="__('Lupa Password')">
    <x-auth-header :title="__('Lupa Password')" :description="__('Masukkan email Anda untuk menerima tautan reset password.')" />

    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-4">
        @csrf

        <div>
            <label for="email" class="mb-1.5 block text-[11px] font-bold tracking-[.03em] text-ink-label uppercase">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" class="inp h-9 w-full" required autofocus
                placeholder="email@example.com">
            @error('email')<p class="hint !text-danger">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="btn btn-p w-full" data-test="email-password-reset-link-button">{{ __('Kirim tautan reset password') }}</button>
    </form>

    <p class="m-0 text-center text-[13px] text-muted">
        {{ __('Atau kembali ke') }} <a href="{{ route('login') }}">{{ __('halaman masuk') }}</a>
    </p>
</x-layouts::auth>
