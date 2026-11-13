<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(): View
    {
        return view('contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'sujet' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
        ], [
            'nom.required' => 'Votre nom est obligatoire.',
            'email.required' => "L'adresse email est obligatoire.",
            'email.email' => "L'adresse email n'est pas valide.",
            'sujet.required' => 'Précisez le sujet de votre message.',
            'message.required' => 'Votre message est vide.',
            'message.min' => 'Votre message doit contenir au moins 10 caractères.',
        ]);

        ContactMessage::create([
            ...$validated,
            'lu' => false,
            'id_utilisateur' => $request->user()?->id_utilisateur,
        ]);

        return back()->with('success', 'Merci ! Votre message a bien été transmis à l’équipe Travel Togo. Nous vous répondrons rapidement.');
    }
}
