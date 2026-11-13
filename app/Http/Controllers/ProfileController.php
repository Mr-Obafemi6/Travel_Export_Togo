<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'utilisateur' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $utilisateur = $request->user();

        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'email' => [
                'required', 'email', 'max:150',
                Rule::unique('utilisateurs', 'email')->ignore($utilisateur->id_utilisateur, 'id_utilisateur'),
            ],
        ], [
            'nom.required' => 'Votre nom est obligatoire.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
        ]);

        $utilisateur->update($validated);

        return back()->with('success', 'Vos informations personnelles ont été mises à jour.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'current_password.required' => 'Votre mot de passe actuel est obligatoire.',
            'current_password.current_password' => 'Le mot de passe actuel est incorrect.',
            'password.confirmed' => 'Les deux nouveaux mots de passe ne sont pas identiques.',
        ]);

        $request->user()->forceFill([
            'mot_de_passe' => Hash::make($validated['password']),
        ])->save();

        return back()->with('success', 'Votre mot de passe a été modifié.');
    }
}
