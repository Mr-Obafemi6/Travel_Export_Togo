@extends('layouts.admin')

@section('title', 'Régions')
@section('heading', 'Régions')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Régions du Togo</h1>
            <p class="text-muted-tt mb-0">Les régions structurent les destinations du catalogue.</p>
        </div>
        <a href="{{ route('admin.regions.create') }}" class="btn btn-tt-green">
            <i class="bi bi-plus-lg me-1"></i>Nouvelle région
        </a>
    </div>

    <div class="panel">
        <div class="table-responsive">
            <table class="table table-tt align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom de la région</th>
                        <th>Destinations</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($regions as $region)
                        <tr>
                            <td class="text-small text-muted-tt">{{ $region->id_region }}</td>
                            <td class="fw-semibold">{{ $region->nom_region }}</td>
                            <td><span class="badge-soft">{{ $region->destinations_count }} destination(s)</span></td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('admin.regions.edit', $region) }}" class="btn btn-light border btn-icon" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.regions.destroy', $region) }}"
                                          onsubmit="return confirm('Supprimer la région « {{ $region->nom_region }} » ?');">
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
                            <td colspan="4" class="text-center text-muted-tt py-4">Aucune région enregistrée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $regions->links() }}</div>
@endsection
