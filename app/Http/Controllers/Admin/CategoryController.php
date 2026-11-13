<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Categorie::withCount('destinations')->orderBy('libelle')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.form', ['categorie' => new Categorie]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'libelle' => ['required', 'string', 'max:60', 'unique:categories,libelle'],
        ], [
            'libelle.required' => 'Le libellé est obligatoire.',
            'libelle.unique' => 'Cette catégorie existe déjà.',
        ]);

        Categorie::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'La catégorie a été créée.');
    }

    public function edit(Categorie $categorie): View
    {
        return view('admin.categories.form', ['categorie' => $categorie]);
    }

    public function update(Request $request, Categorie $categorie): RedirectResponse
    {
        $validated = $request->validate([
            'libelle' => [
                'required', 'string', 'max:60',
                Rule::unique('categories', 'libelle')->ignore($categorie->id_categorie, 'id_categorie'),
            ],
        ], [
            'libelle.required' => 'Le libellé est obligatoire.',
            'libelle.unique' => 'Cette catégorie existe déjà.',
        ]);

        $categorie->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'La catégorie a été mise à jour.');
    }

    public function destroy(Categorie $categorie): RedirectResponse
    {
        $categorie->delete();

        return redirect()->route('admin.categories.index')->with('success', 'La catégorie a été supprimée.');
    }
}
