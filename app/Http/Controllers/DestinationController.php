<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Destination;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DestinationController extends Controller
{
    public function index(Request $request): View
    {
        $filtres = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'region' => ['nullable', 'integer'],
            'categorie' => ['nullable', 'integer'],
        ]);

        $destinations = Destination::query()
            ->with(['region', 'photos', 'categories'])
            ->withCount('excursions')
            ->when($filtres['q'] ?? null, function ($query, $recherche) {
                $query->where('nom_destination', 'like', "%{$recherche}%");
            })
            ->when($filtres['region'] ?? null, function ($query, $idRegion) {
                $query->where('id_region', $idRegion);
            })
            ->when($filtres['categorie'] ?? null, function ($query, $idCategorie) {
                $query->whereHas('categories', fn ($q) => $q->where('categories.id_categorie', $idCategorie));
            })
            ->orderBy('nom_destination')
            ->paginate(9)
            ->withQueryString();

        return view('destinations.index', [
            'destinations' => $destinations,
            'regions' => Region::orderBy('nom_region')->get(),
            'categories' => Categorie::orderBy('libelle')->get(),
            'filtres' => $filtres,
        ]);
    }

    public function show(Destination $destination): View
    {
        $destination->load([
            'region',
            'photos',
            'categories',
            'excursions' => fn ($query) => $query
                ->withSum(['reservations as personnes_reservees' => fn ($q) => $q->actives()], 'nb_personnes')
                ->orderBy('date_excursion'),
        ]);

        return view('destinations.show', [
            'destination' => $destination,
        ]);
    }
}
