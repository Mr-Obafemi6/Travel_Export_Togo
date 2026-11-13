<?php

namespace App\Http\Controllers;

use App\Models\Excursion;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(Request $request): View
    {
        $reservations = $request->user()
            ->reservations()
            ->with(['excursion.destination.region', 'excursion.destination.photos'])
            ->orderByDesc('date_reservation')
            ->paginate(8);

        return view('reservations.index', [
            'reservations' => $reservations,
        ]);
    }

    public function store(Request $request, Excursion $excursion): RedirectResponse
    {
        $validated = $request->validate([
            'nb_personnes' => ['required', 'integer', 'min:1'],
        ], [
            'nb_personnes.required' => 'Indiquez le nombre de personnes.',
            'nb_personnes.min' => 'Une réservation comprend au moins une personne.',
        ]);

        $reservation = DB::transaction(function () use ($request, $excursion, $validated) {
            // Verrouille la ligne pour éviter la surréservation (RG2).
            $excursion = Excursion::whereKey($excursion->getKey())->lockForUpdate()->firstOrFail();

            if ($excursion->date_excursion->isPast()) {
                throw ValidationException::withMessages([
                    'nb_personnes' => 'Cette excursion est déjà passée.',
                ]);
            }

            $placesPrises = $excursion->reservationsActives()->sum('nb_personnes');
            $placesRestantes = $excursion->places_max - $placesPrises;

            if ($validated['nb_personnes'] > $placesRestantes) {
                throw ValidationException::withMessages([
                    'nb_personnes' => $placesRestantes > 0
                        ? "Il ne reste que {$placesRestantes} place(s) pour cette excursion."
                        : 'Cette excursion est complète.',
                ]);
            }

            // Le montant est calculé côté serveur, jamais envoyé par le navigateur (RG3).
            return $request->user()->reservations()->create([
                'id_excursion' => $excursion->id_excursion,
                'nb_personnes' => $validated['nb_personnes'],
                'montant' => (float) $excursion->prix * $validated['nb_personnes'],
                'statut' => Reservation::STATUT_EN_ATTENTE,
            ]);
        });

        return redirect()->route('reservations.index')
            ->with('success', "Votre demande de réservation n°{$reservation->id_reservation} a bien été enregistrée. Elle est en attente de confirmation.");
    }

    public function cancel(Request $request, Reservation $reservation): RedirectResponse
    {
        abort_unless($reservation->id_utilisateur === $request->user()->id_utilisateur, 403);

        $reservation->load('excursion');

        if (! $reservation->peut_etre_annulee) {
            return back()->withErrors([
                'annulation' => "Cette réservation ne peut plus être annulée : l'annulation est possible jusqu'à 48 heures avant l'excursion.",
            ]);
        }

        $reservation->update(['statut' => Reservation::STATUT_ANNULEE]);

        return back()->with('success', "La réservation n°{$reservation->id_reservation} a été annulée. Les places sont de nouveau disponibles.");
    }
}
