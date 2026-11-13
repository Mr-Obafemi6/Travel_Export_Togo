@extends('layouts.app')

@section('title', 'Destinations du Togo — Travel Togo')

@section('content')
    <header class="page-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Destinations</li>
                </ol>
            </nav>
            <span class="eyebrow text-gold mt-3">Le Togo à découvrir</span>
            <h1>Destinations</h1>
            <p>Plages, montagnes, cascades, villages et patrimoine : trouvez l’escapade qui vous ressemble.</p>
        </div>
    </header>

    <section class="section-sm">
        <div class="container">
            <form class="panel p-3 p-lg-4 mb-4" method="GET" action="{{ route('destinations.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-5">
                        <label class="form-label" for="q">Nom de la destination</label>
                        <input type="search" class="form-control" id="q" name="q" value="{{ $filtres['q'] ?? '' }}" placeholder="Ex. Kpalimé, Aného…">
                    </div>
                    <div class="col-sm-6 col-lg-2">
                        <label class="form-label" for="region">Région</label>
                        <select class="form-select" id="region" name="region">
                            <option value="">Toutes</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id_region }}" @selected((int) ($filtres['region'] ?? 0) === $region->id_region)>
                                    {{ $region->nom_region }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6 col-lg-2">
                        <label class="form-label" for="categorie">Catégorie</label>
                        <select class="form-select" id="categorie" name="categorie">
                            <option value="">Toutes</option>
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie->id_categorie }}" @selected((int) ($filtres['categorie'] ?? 0) === $categorie->id_categorie)>
                                    {{ $categorie->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 d-flex gap-2">
                        <button type="submit" class="btn btn-tt-green flex-grow-1"><i class="bi bi-search me-1"></i>Filtrer</button>
                        <a href="{{ route('destinations.index') }}" class="btn btn-light border" title="Réinitialiser"><i class="bi bi-x-lg"></i></a>
                    </div>
                </div>
            </form>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="text-muted-tt mb-0">
                    <strong class="text-tt-green">{{ $destinations->total() }}</strong> destination(s) trouvée(s)
                </p>
            </div>

            <div class="row g-4">
                @forelse($destinations as $destination)
                    <div class="col-md-6 col-lg-4">
                        @include('partials.destination-card', ['destination' => $destination])
                    </div>
                @empty
                    <div class="col-12">
                        <div class="panel empty-state">
                            <i class="bi bi-search"></i>
                            <h3 class="h5">Aucune destination ne correspond à votre recherche</h3>
                            <p>Essayez un autre nom, une autre région ou une autre catégorie.</p>
                            <a href="{{ route('destinations.index') }}" class="btn btn-tt-outline-green">Voir toutes les destinations</a>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $destinations->links() }}
            </div>
        </div>
    </section>
@endsection
