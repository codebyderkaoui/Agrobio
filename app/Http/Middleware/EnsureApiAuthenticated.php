<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * For API routes: return a clean JSON 401 instead of a redirect
 * when the user is not authenticated.
 *
 * Laravel 13 uses this automatically for routes in api.php when
 * the route expectsJson() — but keeping this explicit is safer.
 */
class EnsureApiAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return response()->json([
                'message' => 'Non authentifié. Veuillez vous connecter.',
            ], 401);
        }

        return $next($request);
    }
}
