<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyErpApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $expectedToken = config('services.erp.api_token');
        $givenToken = $request->bearerToken();

        if (! $expectedToken || ! $givenToken || ! hash_equals($expectedToken, $givenToken)) {
            return response()->json([
                'message' => 'Unauthorized.',
            ], 401);
        }

        return $next($request);
    }
}