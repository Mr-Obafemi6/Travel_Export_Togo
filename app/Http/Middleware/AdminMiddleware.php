<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Réserve l'espace d'administration aux comptes administrateurs.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isAdmin()) {
            abort(403, "Cette section est réservée à l'administration de Travel Togo.");
        }

        return $next($request);
    }
}
