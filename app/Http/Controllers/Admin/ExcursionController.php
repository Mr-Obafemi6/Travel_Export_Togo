<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Excursion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ExcursionController extends Controller
{
    public function index(Request $request): View
    {
        $recherche = $request->string('q')->trim()->toString();
        $periode = $request->string('periode')->toString();

        $excursions = Excursion::with('destination.region')
            ->withSum(['reservations as personnes_reservees' => fn ($query) => $query->actives()], 'nb_personnes')
            ->when($recherche !== '', fn ($query) => $query->where('titre', 'like', "%{$recherche}%"))
            ->when($request->filled('destination'), fn ($query) => $query->where('id_destination', $request->integer('destination')))
            ->when($periode === 'avenir', fn ($query) => $query->where('date_excursion', '>', now()))
            ->when($periode === 'passees', fn ($query) => $query->where('date_excursion', '<=', now()))
            ->orderByDesc('date_excursion')
            ->paginate(15)
            ->withQueryString();

        return view('admin.excursions.index', [
            'excursions' => $excursions,
            'destinations' => Destination::orderBy('nom_destination')->get(),
            'recherche' => $recherche,
            'periode' => $periode,
        ]);
    }

    public function create(): View
    {
        return view('admin.excursions.form', [
            'excursion' => new Excursion,
            'destinations' => Destination::orderBy('nom_destination')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateExcursion($request);

        Excursion::create($validated);

        return redirect()->route('admin.excursions.index')
            ->with('success', 'L’excursion a été créée.');
    }

    public function edit(Excursion $excursion): View
    {
        return view('admin.excursions.form', [
            'excursion' => $excursion,
            'destinations' => Destination::orderBy('nom_destination')->get(),
        ]);
    }

    public function update(Request $request, Excursion $excursion): RedirectResponse
    {
        $validated = $this->validateExcursion($request, $excursion);

        $excursion->update($validated);

        return redirect()->route('admin.excursions.index')
            ->with('success', 'L’excursion a été mise à jour.');
    }

    public function destroy(Excursion $excursion): RedirectResponse
    {
        if ($excursion->reservations()->exists()) {
            return back()->withErrors([
                'suppression' => 'Impossible de supprimer cette excursion : des réservations y sont rattachées.',
            ]);
        }

        $excursion->delete();

        return redirect()->route('admin.excursions.index')
            ->with('success', 'L’excursion a été supprimée.');
    }

    private function validateExcursion(Request $request, ?Excursion $excursion = null): array
    {
        $validated = $request->validate([
            'titre' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'prix' => ['required', 'numeric', 'gt:0'],
            'duree_heures' => ['required', 'integer', 'min:1', 'max:72'],
            'places_max' => ['required', 'integer', 'min:1', 'max:500'],
            'date_excursion' => ['required', 'date', 'after:now'],
            'id_destination' => ['required', 'integer', 'exists:destinations,id_destination'],
        ], [
            'titre.required' => 'Le titre de l’excursion est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'prix.required' => 'Le prix est obligatoire.',
            'prix.gt' => 'Le prix doit être supérieur à zéro.',
            'places_max.required' => 'Le nombre de places est obligatoire.',
            'date_excursion.required' => 'La date de départ est obligatoire.',
            'date_excursion.after' => 'La date de départ doit être dans le futur.',
            'id_destination.required' => 'Choisissez une destination.',
        ]);

        if ($excursion && $excursion->exists) {
            $placesPrises = $excursion->reservationsActives()->sum('nb_personnes');

            if ($validated['places_max'] < $placesPrises) {
                throw ValidationException::withMessages([
                    'places_max' => "Impossible de descendre sous {$placesPrises} place(s) : des réservations actives existent déjà.",
                ]);
            }
        }

        return $validated;
    }
}
