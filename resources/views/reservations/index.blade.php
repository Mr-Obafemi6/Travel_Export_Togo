@extends('layouts.app')

@section('title', 'Mes réservations — Travel Togo')

@section('content')
    <header class="page-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Mes réservations</li>
                </ol>
            </nav>
            <span class="eyebrow text-gold mt-3">Espace voyageur</span>
            <h1>Mes réservations</h1>
            <p>Suivez l’état de vos demandes et préparez vos prochaines expériences.</p>
        </div>
    </header>

    <section class="section-sm">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-tt mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div>
            @endif

            @error('annulation')
                <div class="alert alert-danger alert-tt mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $message }}
                </div>
            @enderror

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <p class="text-muted-tt mb-0">
                    <strong class="text-tt-green">{{ $reservations->total() }}</strong> réservation(s) au total
                </p>
                <a href="{{ route('excursions.index') }}" class="btn btn-tt-green">
                    <i class="bi bi-plus-lg me-2"></i>Réserver une excursion
                </a>
            </div>

            @forelse($reservations as $reservation)
                @php
                    $excursion = $reservation->excursion;
                    $destination = $excursion?->destination;
                    $photo = $destination?->photo_principale;
                @endphp

                <article class="panel mb-3">
                    <div class="row g-0">
                        <div class="col-md-3">
                            @if($photo)
                                <img src="{{ $photo->url }}"
                                     alt="{{ $destination->nom_destination }}"
                                     class="w-100 h-100 object-fit-cover"
                                     style="min-height: 11rem;"
                                     loading="lazy">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100 bg-sand text-tt-green fs-1" style="min-height: 11rem;">
                                    <i class="bi bi-sun"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <div class="p-3 p-lg-4">
                                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
                                    <div>
                                        <span class="badge bg-{{ $reservation->statut_badge }} badge-status">{{ $reservation->statut_label }}</span>
                                        <span class="text-small text-muted-tt ms-2">Demande n°{{ $reservation->id_reservation }}</span>
                                    </div>
                                    <strong class="text-tt-green">{{ number_format($reservation->montant, 0, ',', ' ') }} FCFA</strong>
                                </div>

                                <h2 class="h5 mb-1">
                                    @if($excursion)
                                        <a href="{{ route('excursions.show', $excursion) }}" class="text-reset">{{ $excursion->titre }}</a>
                                    @else
                                        Excursion indisponible
                                    @endif
                                </h2>

                                @if($destination)
                                    <p class="text-small text-muted-tt mb-3">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $destination->nom_destination }}
                                        <span class="mx-2">·</span>
                                        <i class="bi bi-signpost-split me-1"></i>{{ $destination->region->nom_region }}
                                    </p>
                                @endif

                                <div class="row g-2 text-small">
                                    <div class="col-sm-4">
                                        <span class="text-muted-tt d-block">Date de l’excursion</span>
                                        <strong>{{ $excursion?->date_excursion->translatedFormat('j F Y à H:i') ?? '—' }}</strong>
                                    </div>
                                    <div class="col-sm-4">
                                        <span class="text-muted-tt d-block">Personnes</span>
                                        <strong>{{ $reservation->nb_personnes }}</strong>
                                    </div>
                                    <div class="col-sm-4">
                                        <span class="text-muted-tt d-block">Demandée le</span>
                                        <strong>{{ $reservation->date_reservation?->translatedFormat('j F Y') }}</strong>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-2 mt-3">
                                    @if($reservation->peut_etre_annulee)
                                        <form method="POST"
                                              action="{{ route('reservations.cancel', $reservation) }}"
                                              onsubmit="return confirm('Confirmez-vous l’annulation de cette réservation ?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-x-circle me-1"></i>Annuler la réservation
                                            </button>
                                        </form>
                                    @endif

                                    @if($excursion)
                                        <a href="{{ route('excursions.show', $excursion) }}" class="btn btn-sm btn-tt-outline-green">
                                            Voir l’excursion
                                        </a>
                                    @endif
                                </div>

                                @if(in_array($reservation->statut, \App\Models\Reservation::STATUTS_ACTIFS, true) && ! $reservation->peut_etre_annulee)
                                    <p class="text-small text-muted-tt mt-2 mb-0">
                                        <i class="bi bi-info-circle me-1"></i>
                                        L’annulation n’est plus possible à moins de 48 heures de l’excursion.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="panel empty-state">
                    <i class="bi bi-calendar-x"></i>
                    <h2 class="h5">Vous n’avez encore aucune réservation</h2>
                    <p>Parcourez les excursions disponibles et laissez-vous inspirer par le Togo.</p>
                    <a href="{{ route('excursions.index') }}" class="btn btn-tt-green">Découvrir les excursions</a>
                </div>
            @endforelse

            <div class="mt-4">
                {{ $reservations->links() }}
            </div>
        </div>
    </section>
@endsection
