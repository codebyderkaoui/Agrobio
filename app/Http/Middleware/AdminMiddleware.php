<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restrict access to admin-role users only.
 *
 * Usage in routes:
 *   Route::middleware(['auth', 'admin'])->group(function () { ... });
 */
class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Accès refusé. Rôle administrateur requis.'], 403);
            }
            abort(403, 'Accès refusé. Rôle administrateur requis.');
        }

        return $next($request);
    }
}
