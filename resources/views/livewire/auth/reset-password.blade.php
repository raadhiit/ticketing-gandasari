<x-layouts::auth :title="__('Reset Password')">
    <x-auth-header :title="__('Reset Password')" :description="__('Masukkan password baru Anda di bawah ini.')" />

    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-4">
        @csrf
        <input type="hidden" name="token" value="{{ request()->route('token') }}">

        <div>
            <label for="email" class="mb-1.5 block text-[11px] font-bold tracking-[.03em] text-ink-label uppercase">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" value="{{ request('email') }}" class="inp h-9 w-full" required autocomplete="email">
            @error('email')<p class="hint !text-danger">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password" class="mb-1.5 block text-[11px] font-bold tracking-[.03em] text-ink-label uppercase">{{ __('Password baru') }}</label>
            <input id="password" name="password" type="password" class="inp h-9 w-full" required autocomplete="new-password"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}">
            @error('password')<p class="hint !text-danger">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password_confirmation" class="mb-1.5 block text-[11px] font-bold tracking-[.03em] text-ink-label uppercase">{{ __('Konfirmasi password') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="inp h-9 w-full" required autocomplete="new-password"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}">
        </div>

        <button type="submit" class="btn btn-p w-full" data-test="reset-password-button">{{ __('Reset Password') }}</button>
    </form>
</x-layouts::auth>
