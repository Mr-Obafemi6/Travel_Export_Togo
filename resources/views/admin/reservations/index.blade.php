@extends('layouts.admin')

@section('title', 'Réservations')
@section('heading', 'Réservations')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Réservations</h1>
            <p class="text-muted-tt mb-0">Confirmez, terminez ou annulez les demandes des voyageurs.</p>
        </div>
    </div>

    <div class="row g-2 mb-3">
        @foreach(\App\Models\Reservation::STATUTS as $valeurStatut)
            <div class="col-6 col-md-3">
                <a href="{{ route('admin.reservations.index', array_filter(['statut' => $valeurStatut, 'q' => $recherche])) }}"
                   class="stat-card d-block text-reset {{ $valeurStatut === $statut ? 'border-success' : '' }}">
                    <div class="stat-label">{{ ucfirst(str_replace('_', ' ', $valeurStatut)) }}</div>
                    <div class="d-flex justify-content-between align-items-end">
                        <span class="stat-value">{{ (int) ($compteurs[$valeurStatut] ?? 0) }}</span>
                        <i class="bi bi-arrow-right text-muted-tt"></i>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <form class="panel p-3 mb-3" method="GET" action="{{ route('admin.reservations.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label" for="q">Recherche</label>
                <input type="search" class="form-control" id="q" name="q" value="{{ $recherche }}" placeholder="Nom, email ou excursion…">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="statut">Statut</label>
                <select class="form-select" id="statut" name="statut">
                    <option value="">Tous les statuts</option>
                    @foreach(\App\Models\Reservation::STATUTS as $valeurStatut)
                        <option value="{{ $valeurStatut }}" @selected($statut === $valeurStatut)>
                            {{ ucfirst(str_replace('_', ' ', $valeurStatut)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-tt-green flex-grow-1"><i class="bi bi-funnel me-1"></i>Filtrer</button>
                <a href="{{ route('admin.reservations.index') }}" class="btn btn-light border" title="Réinitialiser"><i class="bi bi-x-lg"></i></a>
            </div>
        </div>
    </form>

    <div class="panel">
        <div class="table-responsive">
            <table class="table table-tt align-middle">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Client</th>
                        <th>Excursion</th>
                        <th>Date</th>
                        <th>Pers.</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $reservation)
                        <tr>
                            <td class="text-small text-muted-tt">#{{ $reservation->id_reservation }}</td>
                            <td>
                                <div class="fw-semibold">{{ $reservation->utilisateur->nom }}</div>
                                <div class="text-small text-muted-tt">{{ $reservation->utilisateur->email }}</div>
                            </td>
                            <td class="text-small">
                                {{ $reservation->excursion->titre }}
                                <div class="text-muted-tt">{{ $reservation->excursion->destination->nom_destination }}</div>
                            </td>
                            <td class="text-small">
                                {{ $reservation->excursion->date_excursion->translatedFormat('j M Y') }}
                                <div class="text-muted-tt">{{ $reservation->date_reservation->translatedFormat('j M Y') }}</div>
                            </td>
                            <td>{{ $reservation->nb_personnes }}</td>
                            <td class="text-small">{{ number_format($reservation->montant, 0, ',', ' ') }} FCFA</td>
                            <td><span class="badge bg-{{ $reservation->statut_badge }} badge-status">{{ $reservation->statut_label }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('admin.reservations.show', $reservation) }}" class="btn btn-light border btn-sm">Détail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted-tt py-4">Aucune réservation trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $reservations->links() }}</div>
@endsection
