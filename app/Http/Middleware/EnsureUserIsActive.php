<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Déconnecte immédiatement un compte bloqué par l'administration (RG10).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->est_bloque) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Votre compte a été bloqué. Contactez l’administration de Travel Togo.']);
        }

        return $next($request);
    }
}
