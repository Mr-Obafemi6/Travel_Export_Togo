<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Destination;
use App\Models\Photo;
use App\Models\Region;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DestinationController extends Controller
{
    public function index(Request $request): View
    {
        $recherche = $request->string('q')->trim()->toString();

        $destinations = Destination::with(['region', 'photos'])
            ->withCount(['excursions', 'categories'])
            ->when($recherche !== '', fn ($query) => $query->where('nom_destination', 'like', "%{$recherche}%"))
            ->orderBy('nom_destination')
            ->paginate(15)
            ->withQueryString();

        return view('admin.destinations.index', [
            'destinations' => $destinations,
            'recherche' => $recherche,
        ]);
    }

    public function create(): View
    {
        return view('admin.destinations.form', [
            'destination' => new Destination,
            'regions' => Region::orderBy('nom_region')->get(),
            'categories' => Categorie::orderBy('libelle')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateDestination($request);

        $destination = Destination::create([
            'nom_destination' => $validated['nom_destination'],
            'description' => $validated['description'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'id_region' => $validated['id_region'],
        ]);

        $destination->categories()->sync($validated['categories'] ?? []);
        $this->enregistrerPhotos($request, $destination);

        return redirect()->route('admin.destinations.index')
            ->with('success', 'La destination a été créée.');
    }

    public function edit(Destination $destination): View
    {
        $destination->load(['photos', 'categories']);

        return view('admin.destinations.form', [
            'destination' => $destination,
            'regions' => Region::orderBy('nom_region')->get(),
            'categories' => Categorie::orderBy('libelle')->get(),
        ]);
    }

    public function update(Request $request, Destination $destination): RedirectResponse
    {
        $validated = $this->validateDestination($request);

        $destination->update([
            'nom_destination' => $validated['nom_destination'],
            'description' => $validated['description'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'id_region' => $validated['id_region'],
        ]);

        $destination->categories()->sync($validated['categories'] ?? []);

        if (! empty($validated['remove_photos'])) {
            $photos = $destination->photos()->whereIn('id_photo', $validated['remove_photos'])->get();

            foreach ($photos as $photo) {
                $this->supprimerFichier($photo);
                $photo->delete();
            }
        }

        $this->enregistrerPhotos($request, $destination);

        return redirect()->route('admin.destinations.index')
            ->with('success', 'La destination a été mise à jour.');
    }

    public function destroy(Destination $destination): RedirectResponse
    {
        if ($destination->excursions()->exists()) {
            return back()->withErrors([
                'suppression' => 'Impossible de supprimer cette destination : des excursions y sont rattachées.',
            ]);
        }

        foreach ($destination->photos as $photo) {
            $this->supprimerFichier($photo);
        }

        $destination->delete();

        return redirect()->route('admin.destinations.index')
            ->with('success', 'La destination a été supprimée.');
    }

    private function validateDestination(Request $request): array
    {
        return $request->validate([
            'nom_destination' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'id_region' => ['required', 'integer', 'exists:regions,id_region'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['integer', 'exists:categories,id_categorie'],
            'photos' => ['nullable', 'array', 'max:8'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'legendes' => ['nullable', 'array'],
            'legendes.*' => ['nullable', 'string', 'max:150'],
            'remove_photos' => ['nullable', 'array'],
            'remove_photos.*' => ['integer', 'exists:photos,id_photo'],
        ], [
            'nom_destination.required' => 'Le nom de la destination est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'id_region.required' => 'Choisissez une région.',
            'id_region.exists' => 'La région sélectionnée est invalide.',
            'photos.*.image' => 'Chaque fichier doit être une image.',
            'photos.*.mimes' => 'Les images doivent être au format JPG, PNG ou WEBP.',
            'photos.*.max' => 'Chaque image ne doit pas dépasser 4 Mo.',
        ]);
    }

    private function enregistrerPhotos(Request $request, Destination $destination): void
    {
        if (! $request->hasFile('photos')) {
            return;
        }

        foreach ($request->file('photos') as $index => $fichier) {
            $chemin = $fichier->store('destinations', 'public');

            $destination->photos()->create([
                'chemin' => $chemin,
                'legende' => $request->input("legendes.{$index}") ?: $destination->nom_destination,
            ]);
        }
    }

    private function supprimerFichier(Photo $photo): void
    {
        if (! $photo->isDistante()) {
            Storage::disk('public')->delete($photo->chemin);
        }
    }
}
