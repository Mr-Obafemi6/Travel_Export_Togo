@extends('layouts.admin')

@section('title', 'Destinations')
@section('heading', 'Destinations')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Destinations</h1>
            <p class="text-muted-tt mb-0">Gérez les lieux, leurs photos et leurs catégories.</p>
        </div>
        <a href="{{ route('admin.destinations.create') }}" class="btn btn-tt-green">
            <i class="bi bi-plus-lg me-1"></i>Nouvelle destination
        </a>
    </div>

    <form class="panel p-3 mb-3" method="GET" action="{{ route('admin.destinations.index') }}">
        <div class="row g-2 align-items-center">
            <div class="col-md-6">
                <input type="search" name="q" class="form-control" value="{{ $recherche }}" placeholder="Rechercher une destination…">
            </div>
            <div class="col-md-6 d-flex gap-2">
                <button type="submit" class="btn btn-tt-green"><i class="bi bi-search me-1"></i>Rechercher</button>
                @if($recherche !== '')
                    <a href="{{ route('admin.destinations.index') }}" class="btn btn-light border">Réinitialiser</a>
                @endif
            </div>
        </div>
    </form>

    <div class="panel">
        <div class="table-responsive">
            <table class="table table-tt align-middle">
                <thead>
                    <tr>
                        <th>Destination</th>
                        <th>Région</th>
                        <th>Catégories</th>
                        <th>Excursions</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($destinations as $destination)
                        @php
                            $photo = $destination->photo_principale;
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if($photo)
                                        <img src="{{ $photo->url }}" alt="{{ $destination->nom_destination }}" class="rounded" width="56" height="42" style="object-fit: cover;">
                                    @else
                                        <span class="bg-sand rounded d-inline-flex align-items-center justify-content-center text-tt-green" style="width: 56px; height: 42px;">
                                            <i class="bi bi-geo-alt"></i>
                                        </span>
                                    @endif
                                    <div>
                                        <div class="fw-semibold">{{ $destination->nom_destination }}</div>
                                        <div class="text-small text-muted-tt">{{ \Illuminate\Support\Str::limit(strip_tags($destination->description), 60) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-small">{{ $destination->region->nom_region }}</td>
                            <td><span class="badge-soft">{{ $destination->categories_count }}</span></td>
                            <td><span class="badge-soft">{{ $destination->excursions_count }}</span></td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('destinations.show', $destination) }}" class="btn btn-light border btn-icon" title="Voir sur le site" target="_blank" rel="noopener">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                    <a href="{{ route('admin.destinations.edit', $destination) }}" class="btn btn-light border btn-icon" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.destinations.destroy', $destination) }}"
                                          onsubmit="return confirm('Supprimer la destination « {{ $destination->nom_destination }} » ?');">
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
                            <td colspan="5" class="text-center text-muted-tt py-4">Aucune destination trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $destinations->links() }}</div>
@endsection
