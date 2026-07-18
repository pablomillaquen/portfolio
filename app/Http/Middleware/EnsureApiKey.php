<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-API-Key');

        if ($token) {
            $accessToken = PersonalAccessToken::findToken($token);

            if (! $accessToken) {
                return response()->json([
                    'message' => 'Invalid API key.',
                ], 401);
            }

            $request->setUser($accessToken->tokenable);
        }

        return $next($request);
    }
}
