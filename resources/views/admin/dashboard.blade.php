@extends('layouts.admin')

@section('title', 'Tableau de bord')
@section('heading', 'Tableau de bord')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Vue d’ensemble</h1>
            <p class="text-muted-tt mb-0">Suivez l’activité de la plateforme Travel Togo.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.excursions.create') }}" class="btn btn-tt-green">
                <i class="bi bi-plus-lg me-1"></i>Nouvelle excursion
            </a>
            <a href="{{ route('admin.destinations.create') }}" class="btn btn-light border">
                <i class="bi bi-plus-lg me-1"></i>Nouvelle destination
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Réservations</div>
                        <div class="stat-value">{{ $stats['reservations'] }}</div>
                    </div>
                    <span class="stat-icon"><i class="bi bi-journal-check"></i></span>
                </div>
                <a href="{{ route('admin.reservations.index') }}" class="text-small d-inline-block mt-3">Gérer les réservations <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Excursions</div>
                        <div class="stat-value">{{ $stats['excursions'] }}</div>
                    </div>
                    <span class="stat-icon"><i class="bi bi-calendar-event"></i></span>
                </div>
                <a href="{{ route('admin.excursions.index') }}" class="text-small d-inline-block mt-3">Voir le catalogue <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Destinations</div>
                        <div class="stat-value">{{ $stats['destinations'] }}</div>
                    </div>
                    <span class="stat-icon"><i class="bi bi-geo-alt"></i></span>
                </div>
                <a href="{{ route('admin.destinations.index') }}" class="text-small d-inline-block mt-3">Gérer les destinations <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Touristes inscrits</div>
                        <div class="stat-value">{{ $stats['touristes'] }}</div>
                    </div>
                    <span class="stat-icon"><i class="bi bi-people"></i></span>
                </div>
                <a href="{{ route('admin.utilisateurs.index') }}" class="text-small d-inline-block mt-3">Gérer les comptes <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-label">Messages non lus</div>
                <div class="stat-value">{{ $stats['messages_non_lus'] }}</div>
                <a href="{{ route('admin.messages.index', ['filtre' => 'non_lus']) }}" class="text-small d-inline-block mt-2">Ouvrir la boîte de réception <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-label">Régions couvertes</div>
                <div class="stat-value">{{ $stats['regions'] }}</div>
                <a href="{{ route('admin.regions.index') }}" class="text-small d-inline-block mt-2">Gérer les régions <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-label">Comptes au total</div>
                <div class="stat-value">{{ $stats['utilisateurs'] }}</div>
                <a href="{{ route('admin.utilisateurs.index') }}" class="text-small d-inline-block mt-2">Voir les utilisateurs <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-5">
            <div class="panel h-100">
                <div class="panel-header">
                    <h2>Réservations par statut</h2>
                </div>
                <div class="panel-body">
                    @forelse(\App\Models\Reservation::STATUTS as $statut)
                        @php
                            $totalStatut = (int) ($reservationsParStatut[$statut] ?? 0);
                            $part = $stats['reservations'] > 0 ? round($totalStatut / $stats['reservations'] * 100) : 0;
                        @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between text-small mb-1">
                                <span class="text-muted-tt">{{ ucfirst(str_replace('_', ' ', $statut)) }}</span>
                                <strong>{{ $totalStatut }}</strong>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $part }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted-tt mb-0">Aucune réservation enregistrée.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="panel h-100">
                <div class="panel-header">
                    <h2>Prochaines excursions</h2>
                    <a href="{{ route('admin.excursions.index', ['periode' => 'avenir']) }}" class="text-small">Tout voir</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-tt align-middle">
                        <thead>
                            <tr>
                                <th>Excursion</th>
                                <th>Date</th>
                                <th>Places</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($prochainesExcursions as $excursion)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.excursions.edit', $excursion) }}" class="fw-semibold">{{ $excursion->titre }}</a>
                                        <div class="text-small text-muted-tt">{{ $excursion->destination->nom_destination }}</div>
                                    </td>
                                    <td class="text-small">{{ $excursion->date_excursion->translatedFormat('j M Y') }}</td>
                                    <td class="text-small">
                                        <span class="badge bg-{{ $excursion->est_complete ? 'danger' : 'success' }} badge-status">
                                            {{ $excursion->places_restantes }} restantes
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted-tt py-4">Aucune excursion programmée.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <h2>Dernières réservations</h2>
            <a href="{{ route('admin.reservations.index') }}" class="text-small">Tout voir</a>
        </div>
        <div class="table-responsive">
            <table class="table table-tt align-middle">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Client</th>
                        <th>Excursion</th>
                        <th>Personnes</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dernieresReservations as $reservation)
                        <tr>
                            <td class="text-small">#{{ $reservation->id_reservation }}</td>
                            <td>
                                <div class="fw-semibold">{{ $reservation->utilisateur->nom }}</div>
                                <div class="text-small text-muted-tt">{{ $reservation->utilisateur->email }}</div>
                            </td>
                            <td class="text-small">
                                {{ $reservation->excursion->titre }}
                                <div class="text-muted-tt">{{ $reservation->excursion->destination->nom_destination }}</div>
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
                            <td colspan="7" class="text-center text-muted-tt py-4">Aucune réservation pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
