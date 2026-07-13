<?php

namespace App\Http\Controllers;

use App\Models\ErpSsoToken;
use Illuminate\Support\Facades\Auth;

class ErpSsoLoginController extends Controller
{
    public function __invoke(string $token)
    {
        $tokenHash = hash('sha256', $token);

        $ssoToken = ErpSsoToken::with('user')
            ->where('token_hash', $tokenHash)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();

        if (! $ssoToken || ! $ssoToken->user) {
            abort(403, 'Link login tidak valid atau sudah kedaluwarsa.');
        }

        if (! $ssoToken->user->is_active) {
            abort(403, 'Akun ticketing tidak aktif.');
        }

        $ssoToken->update([
            'used_at' => now(),
        ]);

        Auth::login($ssoToken->user);

        request()->session()->regenerate();

        return redirect()->route('tickets.create');
    }
}
