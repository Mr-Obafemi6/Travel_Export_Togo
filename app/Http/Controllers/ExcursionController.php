<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Excursion;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExcursionController extends Controller
{
    public function index(Request $request): View
    {
        $filtres = $request->validate([
            'q' => ['nullable', 'string', 'max:150'],
            'region' => ['nullable', 'integer'],
            'destination' => ['nullable', 'integer'],
        ]);

        $excursions = Excursion::query()
            ->with(['destination.region', 'destination.photos'])
            ->withSum(['reservations as personnes_reservees' => fn ($query) => $query->actives()], 'nb_personnes')
            ->where('date_excursion', '>', now())
            ->when($filtres['q'] ?? null, function ($query, $recherche) {
                $query->where('titre', 'like', "%{$recherche}%");
            })
            ->when($filtres['region'] ?? null, function ($query, $idRegion) {
                $query->whereHas('destination', fn ($q) => $q->where('id_region', $idRegion));
            })
            ->when($filtres['destination'] ?? null, function ($query, $idDestination) {
                $query->where('id_destination', $idDestination);
            })
            ->orderBy('date_excursion')
            ->paginate(9)
            ->withQueryString();

        return view('excursions.index', [
            'excursions' => $excursions,
            'regions' => Region::orderBy('nom_region')->get(),
            'destinations' => Destination::orderBy('nom_destination')->get(),
            'filtres' => $filtres,
        ]);
    }

    public function show(Excursion $excursion): View
    {
        $excursion->load(['destination.region', 'destination.photos'])
            ->loadSum(['reservations as personnes_reservees' => fn ($query) => $query->actives()], 'nb_personnes');

        $autresExcursions = Excursion::with(['destination.photos'])
            ->withSum(['reservations as personnes_reservees' => fn ($query) => $query->actives()], 'nb_personnes')
            ->where('id_destination', $excursion->id_destination)
            ->where('id_excursion', '!=', $excursion->id_excursion)
            ->where('date_excursion', '>', now())
            ->orderBy('date_excursion')
            ->take(3)
            ->get();

        return view('excursions.show', [
            'excursion' => $excursion,
            'autresExcursions' => $autresExcursions,
        ]);
    }
}
