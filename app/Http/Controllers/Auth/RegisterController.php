<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:utilisateurs,email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'nom.required' => 'Votre nom est obligatoire.',
            'email.required' => "L'adresse email est obligatoire.",
            'email.unique' => 'Un compte existe déjà avec cette adresse email.',
            'password.confirmed' => 'Les deux mots de passe ne sont pas identiques.',
        ]);

        $utilisateur = Utilisateur::create([
            'nom' => $validated['nom'],
            'email' => $validated['email'],
            'mot_de_passe' => $validated['password'],
            'role' => Utilisateur::ROLE_TOURISTE,
            'est_bloque' => false,
        ]);

        Auth::login($utilisateur);
        $request->session()->regenerate();

        return redirect()->route('reservations.index')
            ->with('success', 'Votre compte a bien été créé. Bienvenue au Togo !');
    }
}
