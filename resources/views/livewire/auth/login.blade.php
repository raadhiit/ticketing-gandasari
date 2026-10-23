<x-layouts::auth :title="__('Masuk')">
    <x-auth-header :title="__('Masuk')" :description="__('Masukkan email dan password Anda untuk masuk ke sistem ticketing.')" />

    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-4">
        @csrf

        <div>
            <label for="email" class="mb-1.5 block text-[11px] font-bold tracking-[.03em] text-ink-label uppercase">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" class="inp h-9 w-full" required autofocus
                autocomplete="email" placeholder="email@example.com">
            @error('email')<p class="hint !text-danger">{{ $message }}</p>@enderror
        </div>

        <div>
            <div class="mb-1.5 flex items-baseline justify-between">
                <label for="password" class="block text-[11px] font-bold tracking-[.03em] text-ink-label uppercase">{{ __('Password') }}</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-[13px] no-underline">{{ __('Lupa password?') }}</a>
                @endif
            </div>
            <input id="password" name="password" type="password" class="inp h-9 w-full" required autocomplete="current-password">
            @error('password')<p class="hint !text-danger">{{ $message }}</p>@enderror
        </div>

        <label class="flex items-center gap-2 text-sm font-medium text-ink-label">
            <input type="checkbox" name="remember" @checked(old('remember'))> {{ __('Ingat saya') }}
        </label>

        <button type="submit" class="btn btn-p w-full" data-test="login-button">{{ __('Masuk') }}</button>
    </form>
</x-layouts::auth>
