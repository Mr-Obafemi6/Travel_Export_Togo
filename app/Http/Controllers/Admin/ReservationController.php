<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ReservationController extends Controller
{
    private const TRANSITIONS = [
        Reservation::STATUT_EN_ATTENTE => [Reservation::STATUT_CONFIRMEE, Reservation::STATUT_ANNULEE],
        Reservation::STATUT_CONFIRMEE => [Reservation::STATUT_ANNULEE, Reservation::STATUT_TERMINEE],
    ];

    public function index(Request $request): View
    {
        $recherche = $request->string('q')->trim()->toString();
        $statut = $request->string('statut')->toString();

        $reservations = Reservation::with(['utilisateur', 'excursion.destination.region'])
            ->when($recherche !== '', function ($query) use ($recherche) {
                $query->where(function ($q) use ($recherche) {
                    $q->whereHas('utilisateur', function ($utilisateur) use ($recherche) {
                        $utilisateur->where('nom', 'like', "%{$recherche}%")
                            ->orWhere('email', 'like', "%{$recherche}%");
                    })->orWhereHas('excursion', fn ($excursion) => $excursion->where('titre', 'like', "%{$recherche}%"));
                });
            })
            ->when(in_array($statut, Reservation::STATUTS, true), fn ($query) => $query->where('statut', $statut))
            ->orderByDesc('date_reservation')
            ->paginate(15)
            ->withQueryString();

        return view('admin.reservations.index', [
            'reservations' => $reservations,
            'recherche' => $recherche,
            'statut' => $statut,
            'compteurs' => Reservation::query()
                ->selectRaw('statut, COUNT(*) as total')
                ->groupBy('statut')
                ->pluck('total', 'statut'),
        ]);
    }

    public function show(Reservation $reservation): View
    {
        $reservation->load(['utilisateur', 'excursion.destination.region']);

        return view('admin.reservations.show', [
            'reservation' => $reservation,
            'transitions' => self::TRANSITIONS[$reservation->statut] ?? [],
        ]);
    }

    public function updateStatus(Request $request, Reservation $reservation): RedirectResponse
    {
        $validated = $request->validate([
            'statut' => ['required', 'in:'.implode(',', Reservation::STATUTS)],
        ]);

        $nouveauStatut = $validated['statut'];
        $transitionsAutorisees = self::TRANSITIONS[$reservation->statut] ?? [];

        if (! in_array($nouveauStatut, $transitionsAutorisees, true)) {
            throw ValidationException::withMessages([
                'statut' => "Cette réservation ne peut pas passer du statut « {$reservation->statut_label} » à ce statut.",
            ]);
        }

        DB::transaction(function () use ($reservation, $nouveauStatut) {
            $reservation = Reservation::whereKey($reservation->getKey())->lockForUpdate()->firstOrFail();
            $reservation->load('excursion');

            if ($nouveauStatut === Reservation::STATUT_CONFIRMEE && $reservation->excursion->date_excursion->isPast()) {
                throw ValidationException::withMessages([
                    'statut' => 'Impossible de confirmer une réservation pour une excursion passée.',
                ]);
            }

            if ($nouveauStatut === Reservation::STATUT_TERMINEE && $reservation->excursion->date_excursion->isFuture()) {
                throw ValidationException::withMessages([
                    'statut' => 'Une réservation ne peut être terminée qu’après la date de l’excursion.',
                ]);
            }

            $reservation->update(['statut' => $nouveauStatut]);
        });

        return redirect()->route('admin.reservations.show', $reservation)
            ->with('success', 'Le statut de la réservation a été mis à jour.');
    }
}
