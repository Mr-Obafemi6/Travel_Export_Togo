<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => "L'adresse email est obligatoire.",
            'email.email' => "L'adresse email n'est pas valide.",
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        $utilisateur = Utilisateur::where('email', $credentials['email'])->first();

        if ($utilisateur?->est_bloque) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Ce compte est bloqué. Contactez l’administration de Travel Togo.']);
        }

        if (! Auth::attempt($credentials)) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Ces identifiants ne correspondent à aucun compte.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(
            Auth::user()->isAdmin() ? route('admin.dashboard') : route('reservations.index')
        )->with('success', 'Bienvenue, '.Auth::user()->nom.' !');
    }
}
