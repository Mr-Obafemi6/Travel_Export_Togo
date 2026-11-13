@extends('layouts.app')

@section('title', 'Excursions au Togo — Travel Togo')

@section('content')
    <header class="page-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Excursions</li>
                </ol>
            </nav>
            <span class="eyebrow text-gold mt-3">Vivre le Togo</span>
            <h1>Excursions & expériences</h1>
            <p>Randonnées, croisières, patrimoine, plages et rencontres : réservez votre prochaine aventure auprès de prestataires locaux.</p>
        </div>
    </header>

    <section class="section-sm">
        <div class="container">
            <form class="panel p-3 p-lg-4 mb-4" method="GET" action="{{ route('excursions.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-4">
                        <label class="form-label" for="q">Rechercher une excursion</label>
                        <input type="search" class="form-control" id="q" name="q" value="{{ $filtres['q'] ?? '' }}" placeholder="Randonnée, croisière…">
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <label class="form-label" for="region">Région</label>
                        <select class="form-select" id="region" name="region">
                            <option value="">Toutes les régions</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id_region }}" @selected((int) ($filtres['region'] ?? 0) === $region->id_region)>
                                    {{ $region->nom_region }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <label class="form-label" for="destination">Destination</label>
                        <select class="form-select" id="destination" name="destination">
                            <option value="">Toutes les destinations</option>
                            @foreach($destinations as $destination)
                                <option value="{{ $destination->id_destination }}" @selected((int) ($filtres['destination'] ?? 0) === $destination->id_destination)>
                                    {{ $destination->nom_destination }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-tt-green flex-grow-1"><i class="bi bi-search"></i></button>
                        <a href="{{ route('excursions.index') }}" class="btn btn-light border" title="Réinitialiser"><i class="bi bi-x-lg"></i></a>
                    </div>
                </div>
            </form>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="text-muted-tt mb-0">
                    <strong class="text-tt-green">{{ $excursions->total() }}</strong> excursion(s) à venir
                </p>
            </div>

            <div class="row g-4">
                @forelse($excursions as $excursion)
                    <div class="col-md-6 col-lg-4">
                        @include('partials.excursion-card', ['excursion' => $excursion])
                    </div>
                @empty
                    <div class="col-12">
                        <div class="panel empty-state">
                            <i class="bi bi-calendar-x"></i>
                            <h3 class="h5">Aucune excursion à venir ne correspond à votre recherche</h3>
                            <p>Modifiez vos filtres ou revenez bientôt : de nouvelles dates sont régulièrement ajoutées.</p>
                            <a href="{{ route('excursions.index') }}" class="btn btn-tt-outline-green">Voir toutes les excursions</a>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $excursions->links() }}
            </div>
        </div>
    </section>
@endsection
