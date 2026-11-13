@extends('layouts.admin')

@section('title', 'Réservation n°'.$reservation->id_reservation)
@section('heading', 'Réservation n°'.$reservation->id_reservation)

@section('content')
    @php
        $libelles = [
            \App\Models\Reservation::STATUT_EN_ATTENTE => 'Remettre en attente',
            \App\Models\Reservation::STATUT_CONFIRMEE => 'Confirmer la réservation',
            \App\Models\Reservation::STATUT_ANNULEE => 'Annuler la réservation',
            \App\Models\Reservation::STATUT_TERMINEE => 'Marquer comme terminée',
        ];
    @endphp

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Réservation n°{{ $reservation->id_reservation }}</h1>
            <p class="text-muted-tt mb-0">
                Demandée le {{ $reservation->date_reservation->translatedFormat('j F Y à H:i') }}
            </p>
        </div>
        <a href="{{ route('admin.reservations.index') }}" class="btn btn-light border">
            <i class="bi bi-arrow-left me-1"></i>Retour aux réservations
        </a>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="panel mb-3">
                <div class="panel-header">
                    <h2>Excursion réservée</h2>
                    <span class="badge bg-{{ $reservation->statut_badge }} badge-status">{{ $reservation->statut_label }}</span>
                </div>
                <div class="panel-body">
                    @php
                        $excursion = $reservation->excursion;
                        $destination = $excursion->destination;
                        $photo = $destination->photo_principale;
                    @endphp

                    <div class="d-flex gap-3 align-items-start mb-3">
                        @if($photo)
                            <img src="{{ $photo->url }}" alt="{{ $destination->nom_destination }}" class="rounded" width="110" height="82" style="object-fit: cover;">
                        @endif
                        <div>
                            <h3 class="h5 mb-1">{{ $excursion->titre }}</h3>
                            <p class="text-small text-muted-tt mb-1">
                                <i class="bi bi-geo-alt me-1"></i>{{ $destination->nom_destination }} · {{ $destination->region->nom_region }}
                            </p>
                            <p class="text-small text-muted-tt mb-0">
                                <i class="bi bi-calendar-event me-1"></i>{{ $excursion->date_excursion->translatedFormat('l j F Y à H:i') }}
                                <span class="mx-1">·</span>
                                <i class="bi bi-clock me-1"></i>{{ $excursion->duree_heures }} h
                            </p>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-sm-4">
                            <div class="p-3 rounded h-100" style="background: var(--tt-sand);">
                                <div class="text-small text-muted-tt">Personnes</div>
                                <strong class="fs-5 text-tt-green">{{ $reservation->nb_personnes }}</strong>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 rounded h-100" style="background: var(--tt-sand);">
                                <div class="text-small text-muted-tt">Montant total</div>
                                <strong class="fs-5 text-tt-green">{{ number_format($reservation->montant, 0, ',', ' ') }} FCFA</strong>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 rounded h-100" style="background: var(--tt-sand);">
                                <div class="text-small text-muted-tt">Prix unitaire</div>
                                <strong class="fs-5 text-tt-green">{{ number_format($excursion->prix, 0, ',', ' ') }} FCFA</strong>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('excursions.show', $excursion) }}" class="btn btn-tt-outline-green btn-sm mt-3" target="_blank" rel="noopener">
                        <i class="bi bi-box-arrow-up-right me-1"></i>Voir l’excursion sur le site
                    </a>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h2>Client</h2>
                </div>
                <div class="panel-body">
                    <div class="info-line">
                        <span>Nom</span>
                        <span>{{ $reservation->utilisateur->nom }}</span>
                    </div>
                    <div class="info-line">
                        <span>Email</span>
                        <span><a href="mailto:{{ $reservation->utilisateur->email }}">{{ $reservation->utilisateur->email }}</a></span>
                    </div>
                    <div class="info-line">
                        <span>Compte créé le</span>
                        <span>{{ $reservation->utilisateur->date_inscription?->translatedFormat('j F Y') }}</span>
                    </div>
                    <div class="info-line">
                        <span>Statut du compte</span>
                        <span>
                            @if($reservation->utilisateur->est_bloque)
                                <span class="badge bg-danger badge-status">Bloqué</span>
                            @else
                                <span class="badge bg-success badge-status">Actif</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="panel mb-3">
                <div class="panel-header">
                    <h2>Changer le statut</h2>
                </div>
                <div class="panel-body">
                    @if(empty($transitions))
                        <p class="text-muted-tt mb-0">
                            Cette réservation est clôturée : aucun changement de statut n’est possible.
                        </p>
                    @else
                        <p class="text-small text-muted-tt">
                            Statut actuel : <strong class="text-tt-green">{{ $reservation->statut_label }}</strong>.
                            Seules les transitions autorisées sont proposées.
                        </p>

                        @foreach($transitions as $transition)
                            <form method="POST" action="{{ route('admin.reservations.statut', $reservation) }}" class="mb-2">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="statut" value="{{ $transition }}">
                                <button type="submit" class="btn w-100 {{ $transition === \App\Models\Reservation::STATUT_ANNULEE ? 'btn-outline-danger' : 'btn-tt-green' }}">
                                    {{ $libelles[$transition] ?? ucfirst(str_replace('_', ' ', $transition)) }}
                                </button>
                            </form>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h2>Récapitulatif</h2>
                </div>
                <div class="panel-body">
                    <div class="info-line">
                        <span>Référence</span>
                        <span>#{{ $reservation->id_reservation }}</span>
                    </div>
                    <div class="info-line">
                        <span>Statut</span>
                        <span>{{ $reservation->statut_label }}</span>
                    </div>
                    <div class="info-line">
                        <span>Demandée le</span>
                        <span>{{ $reservation->date_reservation->translatedFormat('j M Y · H:i') }}</span>
                    </div>
                    <div class="info-line">
                        <span>Annulation voyageur</span>
                        <span>{{ $reservation->peut_etre_annulee ? 'Encore possible' : 'Fermée' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
