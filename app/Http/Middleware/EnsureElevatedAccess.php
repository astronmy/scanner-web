<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureElevatedAccess
{
    /**
     * Usuarios con rol "user" solo pueden usar el escáner y el listado en modo lectura + export.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->isUser()) {
            abort(403);
        }

        return $next($request);
    }
}
