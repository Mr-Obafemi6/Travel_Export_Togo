@extends('layouts.admin')

@section('title', 'Catégories')
@section('heading', 'Catégories')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Catégories de destinations</h1>
            <p class="text-muted-tt mb-0">Plage, montagne, patrimoine… aidez les voyageurs à filtrer.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-tt-green">
            <i class="bi bi-plus-lg me-1"></i>Nouvelle catégorie
        </a>
    </div>

    <div class="panel">
        <div class="table-responsive">
            <table class="table table-tt align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Libellé</th>
                        <th>Destinations</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $categorie)
                        <tr>
                            <td class="text-small text-muted-tt">{{ $categorie->id_categorie }}</td>
                            <td class="fw-semibold">{{ $categorie->libelle }}</td>
                            <td><span class="badge-soft">{{ $categorie->destinations_count }} destination(s)</span></td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('admin.categories.edit', $categorie) }}" class="btn btn-light border btn-icon" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $categorie) }}"
                                          onsubmit="return confirm('Supprimer la catégorie « {{ $categorie->libelle }} » ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-icon" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted-tt py-4">Aucune catégorie enregistrée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $categories->links() }}</div>
@endsection
