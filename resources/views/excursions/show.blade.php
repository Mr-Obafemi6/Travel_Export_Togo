@extends('layouts.app')

@section('title', $excursion->titre.' — Travel Togo')

@section('content')
    @php
        $photo = $excursion->destination->photo_principale;
        $passee = $excursion->date_excursion->isPast();
        $complete = $excursion->places_restantes <= 0;
        $pourcentage = $excursion->places_max > 0
            ? min(100, round($excursion->places_restantes / $excursion->places_max * 100))
            : 0;
    @endphp

    <div class="container pt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('excursions.index') }}">Excursions</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $excursion->titre }}</li>
            </ol>
        </nav>

        <div class="detail-hero mb-4">
            @if($photo)
                <img src="{{ $photo->url }}" alt="{{ $excursion->destination->nom_destination }}">
            @else
                <div class="d-flex align-items-center justify-content-center bg-sand text-tt-green" style="min-height: 26rem;">
                    <i class="bi bi-sun fs-1"></i>
                </div>
            @endif
            <div class="overlay"></div>
            <div class="overlay-content">
                <span class="badge rounded-pill text-bg-warning mb-2">{{ $excursion->destination->region->nom_region }}</span>
                <h1 class="mb-2">{{ $excursion->titre }}</h1>
                <p class="mb-0 text-white-50">
                    <i class="bi bi-geo-alt me-1"></i>
                    <a class="text-white" href="{{ route('destinations.show', $excursion->destination) }}">{{ $excursion->destination->nom_destination }}</a>
                </p>
            </div>
        </div>
    </div>

    <section class="pb-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="panel p-4 p-lg-5 mb-4">
                        <span class="eyebrow">Le programme</span>
                        <h2 class="section-title h3">Vivez l’expérience</h2>
                        <div class="text-muted-tt" style="white-space: pre-line;">{{ $excursion->description }}</div>

                        <div class="row g-3 mt-4">
                            <div class="col-sm-6">
                                <div class="feature py-3 px-3">
                                    <i class="bi bi-calendar-event text-gold fs-5"></i>
                                    <div class="small text-muted-tt mt-2">Date de départ</div>
                                    <strong>{{ $excursion->date_excursion->translatedFormat('l j F Y à H:i') }}</strong>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="feature py-3 px-3">
                                    <i class="bi bi-clock text-gold fs-5"></i>
                                    <div class="small text-muted-tt mt-2">Durée</div>
                                    <strong>{{ $excursion->duree_heures }} heure(s)</strong>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="feature py-3 px-3">
                                    <i class="bi bi-people text-gold fs-5"></i>
                                    <div class="small text-muted-tt mt-2">Places restantes</div>
                                    <strong>{{ $excursion->places_restantes }} / {{ $excursion->places_max }}</strong>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="feature py-3 px-3">
                                    <i class="bi bi-cash-coin text-gold fs-5"></i>
                                    <div class="small text-muted-tt mt-2">Tarif</div>
                                    <strong>{{ number_format($excursion->prix, 0, ',', ' ') }} FCFA / personne</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($autresExcursions->isNotEmpty())
                        <div class="panel p-4 p-lg-5">
                            <h2 class="h4 mb-3">À découvrir aussi à {{ $excursion->destination->nom_destination }}</h2>
                            <div class="row g-3">
                                @foreach($autresExcursions as $autre)
                                    <div class="col-md-6">
                                        @include('partials.excursion-card', ['excursion' => $autre])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-lg-4">
                    <div class="sidebar-card mb-4">
                        <span class="eyebrow">Réservation</span>
                        <div class="d-flex align-items-baseline gap-2 my-2">
                            <span class="price-big">{{ number_format($excursion->prix, 0, ',', ' ') }}</span>
                            <span class="text-muted-tt">FCFA <small>/ personne</small></span>
                        </div>
                        <div class="d-flex justify-content-between text-small text-muted-tt mb-1 mt-3">
                            <span>{{ $excursion->places_restantes }} place(s) restante(s)</span>
                            <span>{{ $excursion->places_max }} au total</span>
                        </div>
                        <div class="progress mb-4" role="progressbar" aria-valuenow="{{ $pourcentage }}" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar" style="width: {{ $pourcentage }}%"></div>
                        </div>

                        @if($passee)
                            <div class="alert alert-secondary mb-0">
                                <i class="bi bi-clock-history me-2"></i>Cette excursion est déjà passée.
                            </div>
                        @elseif($complete)
                            <div class="alert alert-warning mb-0">
                                <i class="bi bi-people me-2"></i>Cette excursion est complète. Contactez-nous pour être informé d’une prochaine date.
                            </div>
                        @else
                            @auth
                                <form method="POST" action="{{ route('reservations.store', $excursion) }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label" for="nb_personnes">Nombre de personnes</label>
                                        <input type="number"
                                               class="form-control @error('nb_personnes') is-invalid @enderror"
                                               id="nb_personnes"
                                               name="nb_personnes"
                                               min="1"
                                               max="{{ $excursion->places_restantes }}"
                                               value="{{ old('nb_personnes', 1) }}"
                                               required>
                                        @error('nb_personnes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center p-3 rounded-3 mb-3" style="background: var(--tt-sand);">
                                        <span class="text-muted-tt text-small">Montant estimé</span>
                                        <strong id="montantTotal" class="text-tt-green fs-5">
                                            {{ number_format($excursion->prix, 0, ',', ' ') }} FCFA
                                        </strong>
                                    </div>

                                    <button type="submit" class="btn btn-tt w-100 btn-lg">
                                        <i class="bi bi-calendar2-plus me-2"></i>Demander la réservation
                                    </button>
                                    <p class="text-small text-muted-tt mt-3 mb-0">
                                        Votre demande sera étudiée par l’administration. Le montant est calculé automatiquement selon le nombre de personnes.
                                    </p>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-tt w-100 btn-lg">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter pour réserver
                                </a>
                                <p class="text-small text-muted-tt mt-3 mb-0">
                                    Pas encore de compte ? <a href="{{ route('register') }}">Créez-en un gratuitement</a> pour envoyer votre demande.
                                </p>
                            @endauth
                        @endif
                    </div>

                    <div class="sidebar-card">
                        <h2 class="h6 text-uppercase text-muted-tt letter-spacing">Bon à savoir</h2>
                        <ul class="list-unstyled text-small text-muted-tt mb-0">
                            <li class="mb-2"><i class="bi bi-check2-circle text-tt-green me-2"></i>Annulation gratuite jusqu’à 48 h avant l’excursion.</li>
                            <li class="mb-2"><i class="bi bi-check2-circle text-tt-green me-2"></i>Le montant est confirmé lors du traitement de votre demande.</li>
                            <li><i class="bi bi-check2-circle text-tt-green me-2"></i>Votre demande apparaît immédiatement dans « Mes réservations ».</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    @auth
        <script>
            (function () {
                var input = document.getElementById('nb_personnes');
                var total = document.getElementById('montantTotal');
                var prix = {{ (float) $excursion->prix }};

                if (!input || !total) return;

                function formatMontant(valeur) {
                    return new Intl.NumberFormat('fr-FR', { maximumFractionDigits: 0 }).format(valeur) + ' FCFA';
                }

                function actualiser() {
                    var nombre = Math.max(1, parseInt(input.value, 10) || 1);
                    total.textContent = formatMontant(prix * nombre);
                }

                input.addEventListener('input', actualiser);
                actualiser();
            })();
        </script>
    @endauth
@endpush
