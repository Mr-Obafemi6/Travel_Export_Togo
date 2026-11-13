@extends('layouts.admin')

@section('title', 'Excursions')
@section('heading', 'Excursions')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Excursions</h1>
            <p class="text-muted-tt mb-0">Programmez les départs et surveillez les places disponibles.</p>
        </div>
        <a href="{{ route('admin.excursions.create') }}" class="btn btn-tt-green">
            <i class="bi bi-plus-lg me-1"></i>Nouvelle excursion
        </a>
    </div>

    <form class="panel p-3 mb-3" method="GET" action="{{ route('admin.excursions.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label" for="q">Recherche</label>
                <input type="search" class="form-control" id="q" name="q" value="{{ $recherche }}" placeholder="Titre de l’excursion…">
            </div>
            <div class="col-md-3">
                <label class="form-label" for="destination">Destination</label>
                <select class="form-select" id="destination" name="destination">
                    <option value="">Toutes</option>
                    @foreach($destinations as $destination)
                        <option value="{{ $destination->id_destination }}" @selected(request('destination') == $destination->id_destination)>
                            {{ $destination->nom_destination }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="periode">Période</label>
                <select class="form-select" id="periode" name="periode">
                    <option value="">Toutes les dates</option>
                    <option value="avenir" @selected($periode === 'avenir')>À venir</option>
                    <option value="passees" @selected($periode === 'passees')>Passées</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-tt-green flex-grow-1"><i class="bi bi-search"></i></button>
                <a href="{{ route('admin.excursions.index') }}" class="btn btn-light border" title="Réinitialiser"><i class="bi bi-x-lg"></i></a>
            </div>
        </div>
    </form>

    <div class="panel">
        <div class="table-responsive">
            <table class="table table-tt align-middle">
                <thead>
                    <tr>
                        <th>Excursion</th>
                        <th>Destination</th>
                        <th>Date</th>
                        <th>Tarif</th>
                        <th>Places</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($excursions as $excursion)
                        @php
                            $passee = $excursion->date_excursion->isPast();
                            $reservees = (int) ($excursion->personnes_reservees ?? 0);
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $excursion->titre }}</div>
                                <div class="text-small text-muted-tt">{{ $excursion->duree_heures }} h</div>
                            </td>
                            <td class="text-small">
                                {{ $excursion->destination->nom_destination }}
                                <div class="text-muted-tt">{{ $excursion->destination->region->nom_region }}</div>
                            </td>
                            <td class="text-small">{{ $excursion->date_excursion->translatedFormat('j M Y · H:i') }}</td>
                            <td class="text-small">{{ number_format($excursion->prix, 0, ',', ' ') }} FCFA</td>
                            <td class="text-small">
                                <div class="fw-semibold">{{ $reservees }} / {{ $excursion->places_max }}</div>
                                <div class="text-muted-tt">{{ $excursion->places_restantes }} restante(s)</div>
                            </td>
                            <td>
                                @if($passee)
                                    <span class="badge bg-secondary badge-status">Passée</span>
                                @elseif($excursion->est_complete)
                                    <span class="badge bg-danger badge-status">Complète</span>
                                @else
                                    <span class="badge bg-success badge-status">Ouverte</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('excursions.show', $excursion) }}" class="btn btn-light border btn-icon" title="Voir sur le site" target="_blank" rel="noopener">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                    <a href="{{ route('admin.excursions.edit', $excursion) }}" class="btn btn-light border btn-icon" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.excursions.destroy', $excursion) }}"
                                          onsubmit="return confirm('Supprimer l’excursion « {{ $excursion->titre }} » ?');">
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
                            <td colspan="7" class="text-center text-muted-tt py-4">Aucune excursion trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $excursions->links() }}</div>
@endsection
