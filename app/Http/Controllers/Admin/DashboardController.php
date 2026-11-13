<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Destination;
use App\Models\Excursion;
use App\Models\Region;
use App\Models\Reservation;
use App\Models\Utilisateur;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $reservationsParStatut = Reservation::query()
            ->selectRaw('statut, COUNT(*) as total')
            ->groupBy('statut')
            ->pluck('total', 'statut');

        $dernieresReservations = Reservation::with(['utilisateur', 'excursion.destination'])
            ->orderByDesc('date_reservation')
            ->take(8)
            ->get();

        $prochainesExcursions = Excursion::with('destination')
            ->withSum(['reservations as personnes_reservees' => fn ($query) => $query->actives()], 'nb_personnes')
            ->where('date_excursion', '>', now())
            ->orderBy('date_excursion')
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'stats' => [
                'destinations' => Destination::count(),
                'excursions' => Excursion::count(),
                'utilisateurs' => Utilisateur::count(),
                'touristes' => Utilisateur::where('role', Utilisateur::ROLE_TOURISTE)->count(),
                'regions' => Region::count(),
                'reservations' => Reservation::count(),
                'messages_non_lus' => ContactMessage::where('lu', false)->count(),
            ],
            'reservationsParStatut' => $reservationsParStatut,
            'dernieresReservations' => $dernieresReservations,
            'prochainesExcursions' => $prochainesExcursions,
        ]);
    }
}
