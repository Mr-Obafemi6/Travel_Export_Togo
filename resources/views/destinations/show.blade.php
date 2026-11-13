@extends('layouts.app')

@section('title', $destination->nom_destination.' — Travel Togo')

@section('content')
    @php
        $photoPrincipale = $destination->photos->first();
        $excursionsAVenir = $destination->excursions->filter(fn ($excursion) => $excursion->date_excursion->isFuture());
    @endphp

    <div class="container pt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('destinations.index') }}">Destinations</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $destination->nom_destination }}</li>
            </ol>
        </nav>

        <div class="detail-hero mb-4">
            @if($photoPrincipale)
                <img id="mainPhoto" src="{{ $photoPrincipale->url }}" alt="{{ $photoPrincipale->legende ?: $destination->nom_destination }}">
            @else
                <div class="d-flex align-items-center justify-content-center bg-sand text-tt-green" style="min-height: 26rem;">
                    <i class="bi bi-geo-alt fs-1"></i>
                </div>
            @endif
            <div class="overlay"></div>
            <div class="overlay-content">
                <span class="badge-region position-static d-inline-block mb-2">{{ $destination->region->nom_region }}</span>
                <h1 class="mb-2">{{ $destination->nom_destination }}</h1>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($destination->categories as $categorie)
                        <span class="badge rounded-pill text-bg-light">{{ $categorie->libelle }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <section class="pb-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="panel p-4 p-lg-5 mb-4">
                        <span class="eyebrow">Présentation</span>
                        <h2 class="section-title h3">À propos de {{ $destination->nom_destination }}</h2>
                        <div class="text-muted-tt" style="white-space: pre-line;">{{ $destination->description }}</div>
                    </div>

                    @if($destination->photos->count() > 1)
                        <div class="panel p-4 mb-4">
                            <h2 class="h5 mb-3">Galerie photos</h2>
                            <div class="row g-2">
                                @foreach($destination->photos as $photo)
                                    <div class="col-4 col-md-3">
                                        <img src="{{ $photo->url }}"
                                             alt="{{ $photo->legende ?: $destination->nom_destination }}"
                                             class="gallery-thumb"
                                             data-src="{{ $photo->url }}"
                                             loading="lazy">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="panel p-4 p-lg-5">
                        <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
                            <div>
                                <span class="eyebrow">À vivre sur place</span>
                                <h2 class="h3 mb-0">Excursions disponibles</h2>
                            </div>
                            <a href="{{ route('excursions.index', ['destination' => $destination->id_destination]) }}" class="btn btn-tt-outline-green btn-sm">Tout voir</a>
                        </div>

                        @if($excursionsAVenir->isEmpty())
                            <div class="empty-state">
                                <i class="bi bi-calendar-x"></i>
                                <p class="mb-0">Aucune excursion n’est programmée pour le moment dans cette destination.</p>
                            </div>
                        @else
                            <div class="row g-3">
                                @foreach($excursionsAVenir as $excursion)
                                    <div class="col-md-6">
                                        @include('partials.excursion-card', ['excursion' => $excursion])
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="sidebar-card mb-4">
                        <h2 class="h5 mb-3">Informations pratiques</h2>
                        <div class="info-line">
                            <span>Région</span>
                            <span>{{ $destination->region->nom_region }}</span>
                        </div>
                        <div class="info-line">
                            <span>Catégories</span>
                            <span>{{ $destination->categories->pluck('libelle')->join(', ') ?: '—' }}</span>
                        </div>
                        <div class="info-line">
                            <span>Excursions à venir</span>
                            <span>{{ $excursionsAVenir->count() }}</span>
                        </div>
                        @if($destination->latitude && $destination->longitude)
                            <div class="info-line">
                                <span>Coordonnées</span>
                                <span>{{ $destination->latitude }}, {{ $destination->longitude }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="sidebar-card" style="background: var(--tt-sand);">
                        <h2 class="h5">Besoin d’un conseil ?</h2>
                        <p class="text-muted-tt text-small">Notre équipe vous aide à organiser votre séjour et à choisir les expériences adaptées à vos envies.</p>
                        <a href="{{ route('contact.create') }}" class="btn btn-tt-green w-100">
                            <i class="bi bi-envelope me-2"></i>Nous contacter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.gallery-thumb').forEach(function (thumb) {
            thumb.addEventListener('click', function () {
                var main = document.getElementById('mainPhoto');
                if (main) {
                    main.src = thumb.dataset.src;
                }
            });
        });
    </script>
@endpush
