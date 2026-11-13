@extends('layouts.app')

@section('title', 'Travel Togo — Le Togo à hauteur d’émotion')

@section('content')
    <section class="hero">
        <div class="container hero-content">
            <span class="eyebrow">Découvrir · Vivre · Partager</span>
            <h1>Le Togo,<br>à hauteur <em>d’émotion.</em></h1>
            <p class="hero-lead">
                Des plages du Golfe de Guinée aux montagnes Kabyè, des cascades de Kpalimé aux
                marchés colorés de Lomé : préparez votre voyage et réservez vos expériences locales.
            </p>
            <div class="hero-stats">
                <div class="hero-stat">
                    <strong>{{ $stats['destinations'] }}</strong>
                    <span>destinations</span>
                </div>
                <div class="hero-stat">
                    <strong>{{ $stats['excursions'] }}</strong>
                    <span>excursions à venir</span>
                </div>
                <div class="hero-stat">
                    <strong>5</strong>
                    <span>régions à explorer</span>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-3 mt-4">
                <a href="{{ route('excursions.index') }}" class="btn btn-tt btn-lg">
                    <i class="bi bi-compass me-2"></i>Explorer les excursions
                </a>
                <a href="{{ route('destinations.index') }}" class="btn btn-tt-outline btn-lg">
                    Voir les destinations
                </a>
            </div>
        </div>
    </section>

    <div class="container">
        <form class="search-card" method="GET" action="{{ route('destinations.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-lg-5">
                    <label class="form-label" for="q">Où souhaitez-vous aller ?</label>
                    <input type="search" class="form-control" id="q" name="q" placeholder="Kpalimé, Lomé, Dapaong…">
                </div>
                <div class="col-lg-4">
                    <label class="form-label" for="region">Région</label>
                    <select class="form-select" id="region" name="region">
                        <option value="">Toutes les régions</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->id_region }}">{{ $region->nom_region }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3">
                    <button class="btn btn-tt-green w-100" type="submit">
                        <i class="bi bi-search me-2"></i>Rechercher
                    </button>
                </div>
            </div>
        </form>
    </div>

    <section class="section">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
                <div>
                    <span class="eyebrow">Escales incontournables</span>
                    <h2 class="section-title">Destinations populaires</h2>
                    <p class="section-lead mb-0">Une sélection de lieux qui racontent la richesse et la diversité du Togo.</p>
                </div>
                <a href="{{ route('destinations.index') }}" class="btn btn-tt-outline-green">
                    Toutes les destinations <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-4">
                @forelse($destinations as $destination)
                    <div class="col-md-6 col-lg-4">
                        @include('partials.destination-card', ['destination' => $destination])
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="bi bi-geo-alt"></i>
                            <p class="mb-0">Les premières destinations seront bientôt publiées.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="section bg-sand">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
                <div>
                    <span class="eyebrow">Expériences locales</span>
                    <h2 class="section-title">Prochaines excursions</h2>
                    <p class="section-lead mb-0">Réservez votre place auprès de guides et prestataires togolais.</p>
                </div>
                <a href="{{ route('excursions.index') }}" class="btn btn-tt-outline-green">
                    Toutes les excursions <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-4">
                @forelse($excursions as $excursion)
                    <div class="col-md-6 col-lg-4">
                        @include('partials.excursion-card', ['excursion' => $excursion])
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="bi bi-sun"></i>
                            <p class="mb-0">De nouvelles excursions seront programmées très prochainement.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="text-center mb-5">
                <span class="eyebrow justify-content-center">Voyager autrement</span>
                <h2 class="section-title">Pourquoi choisir Travel Togo ?</h2>
                <p class="section-lead mx-auto">Une plateforme pensée pour les voyageurs curieux et les acteurs du tourisme togolais.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature">
                        <span class="feature-icon"><i class="bi bi-map"></i></span>
                        <h3 class="h5">Une information centralisée</h3>
                        <p class="text-muted-tt mb-0">Destinations, photos, régions et catégories réunies au même endroit pour préparer sereinement votre séjour.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature">
                        <span class="feature-icon"><i class="bi bi-calendar2-check"></i></span>
                        <h3 class="h5">Une réservation simple</h3>
                        <p class="text-muted-tt mb-0">Choisissez votre excursion, le nombre de personnes et envoyez votre demande en quelques clics.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature">
                        <span class="feature-icon"><i class="bi bi-shield-check"></i></span>
                        <h3 class="h5">Des places maîtrisées</h3>
                        <p class="text-muted-tt mb-0">Les disponibilités sont contrôlées en temps réel pour éviter les surréservations et garantir votre place.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-sm">
        <div class="container">
            <div class="p-4 p-lg-5 rounded-tt text-white" style="background: linear-gradient(115deg, #0b3d2e, #1f7a5c);">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <span class="eyebrow text-gold">Prêt à partir ?</span>
                        <h2 class="section-title text-white mb-2">Votre prochaine aventure togolaise commence ici.</h2>
                        <p class="mb-0 text-white-50">Créez votre compte gratuitement, réservez vos excursions et suivez l’état de vos demandes.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        @auth
                            <a href="{{ route('excursions.index') }}" class="btn btn-tt btn-lg">Réserver une excursion</a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-tt btn-lg">Créer mon compte</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
