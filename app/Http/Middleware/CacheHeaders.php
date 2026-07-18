<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $path = $request->path();

        if (str_starts_with($path, 'build/assets/')) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
        } elseif ($path === '/' || $path === '') {
            $response->headers->set('Cache-Control', 'no-cache');
        }

        return $response;
    }
}
