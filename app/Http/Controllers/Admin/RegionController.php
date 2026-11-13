<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegionController extends Controller
{
    public function index(): View
    {
        return view('admin.regions.index', [
            'regions' => Region::withCount('destinations')->orderBy('nom_region')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.regions.form', ['region' => new Region]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom_region' => ['required', 'string', 'max:60', 'unique:regions,nom_region'],
        ], [
            'nom_region.required' => 'Le nom de la région est obligatoire.',
            'nom_region.unique' => 'Cette région existe déjà.',
        ]);

        Region::create($validated);

        return redirect()->route('admin.regions.index')->with('success', 'La région a été créée.');
    }

    public function edit(Region $region): View
    {
        return view('admin.regions.form', ['region' => $region]);
    }

    public function update(Request $request, Region $region): RedirectResponse
    {
        $validated = $request->validate([
            'nom_region' => [
                'required', 'string', 'max:60',
                Rule::unique('regions', 'nom_region')->ignore($region->id_region, 'id_region'),
            ],
        ], [
            'nom_region.required' => 'Le nom de la région est obligatoire.',
            'nom_region.unique' => 'Cette région existe déjà.',
        ]);

        $region->update($validated);

        return redirect()->route('admin.regions.index')->with('success', 'La région a été mise à jour.');
    }

    public function destroy(Region $region): RedirectResponse
    {
        if ($region->destinations()->exists()) {
            return back()->withErrors([
                'suppression' => 'Impossible de supprimer cette région : des destinations y sont rattachées.',
            ]);
        }

        $region->delete();

        return redirect()->route('admin.regions.index')->with('success', 'La région a été supprimée.');
    }
}
