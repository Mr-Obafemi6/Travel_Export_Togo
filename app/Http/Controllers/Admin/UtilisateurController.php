<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UtilisateurController extends Controller
{
    public function index(Request $request): View
    {
        $recherche = $request->string('q')->trim()->toString();
        $role = $request->string('role')->toString();

        $utilisateurs = Utilisateur::query()
            ->withCount('reservations')
            ->when($recherche !== '', function ($query) use ($recherche) {
                $query->where(function ($q) use ($recherche) {
                    $q->where('nom', 'like', "%{$recherche}%")
                        ->orWhere('email', 'like', "%{$recherche}%");
                });
            })
            ->when(in_array($role, [Utilisateur::ROLE_TOURISTE, Utilisateur::ROLE_ADMINISTRATEUR], true),
                fn ($query) => $query->where('role', $role))
            ->orderByDesc('date_inscription')
            ->paginate(15)
            ->withQueryString();

        return view('admin.utilisateurs.index', [
            'utilisateurs' => $utilisateurs,
            'recherche' => $recherche,
            'role' => $role,
        ]);
    }

    public function updateBlock(Request $request, Utilisateur $utilisateur): RedirectResponse
    {
        if ($utilisateur->id_utilisateur === $request->user()->id_utilisateur) {
            return back()->withErrors(['blocage' => 'Vous ne pouvez pas bloquer votre propre compte.']);
        }

        if ($utilisateur->isAdmin()) {
            return back()->withErrors(['blocage' => 'Un compte administrateur ne peut pas être bloqué.']);
        }

        $utilisateur->update(['est_bloque' => ! $utilisateur->est_bloque]);

        return back()->with('success', $utilisateur->est_bloque
            ? 'Le compte de '.$utilisateur->nom.' a été bloqué.'
            : 'Le compte de '.$utilisateur->nom.' a été réactivé.');
    }
}
