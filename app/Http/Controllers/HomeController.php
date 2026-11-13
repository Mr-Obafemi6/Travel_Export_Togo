<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Excursion;
use App\Models\Region;
use App\Models\Reservation;
use App\Models\Utilisateur;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $destinations = Destination::with(['region', 'photos', 'categories'])
            ->withCount('excursions')
            ->orderBy('nom_destination')
            ->take(6)
            ->get();

        $excursions = Excursion::with(['destination.region', 'destination.photos'])
            ->withSum(['reservations as personnes_reservees' => fn ($query) => $query->actives()], 'nb_personnes')
            ->where('date_excursion', '>', now())
            ->orderBy('date_excursion')
            ->take(6)
            ->get();

        return view('home', [
            'destinations' => $destinations,
            'excursions' => $excursions,
            'regions' => Region::orderBy('nom_region')->get(),
            'stats' => [
                'destinations' => Destination::count(),
                'excursions' => Excursion::where('date_excursion', '>', now())->count(),
                'touristes' => Utilisateur::where('role', Utilisateur::ROLE_TOURISTE)->count(),
                'reservations' => Reservation::where('statut', Reservation::STATUT_CONFIRMEE)->count(),
            ],
        ]);
    }
}
