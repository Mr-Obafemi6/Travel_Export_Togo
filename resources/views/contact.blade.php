@extends('layouts.app')

@section('title', 'Contact — Travel Togo')

@section('content')
    <header class="page-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Contact</li>
                </ol>
            </nav>
            <span class="eyebrow text-gold mt-3">Parlons de votre voyage</span>
            <h1>Contactez-nous</h1>
            <p>Une question, une demande particulière ou une envie de séjour sur mesure ? Notre équipe vous répond.</p>
        </div>
    </header>

    <section class="section-sm">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="sidebar-card mb-4">
                        <span class="eyebrow">Nos coordonnées</span>
                        <h2 class="h4 mt-2 mb-3">L’équipe Travel Togo</h2>
                        <p class="text-muted-tt">Nous sommes basés à Lomé et connaissons chaque région du pays. Écrivez-nous, nous vous conseillerons avec plaisir.</p>

                        <div class="d-flex gap-3 align-items-start mb-3">
                            <span class="feature-icon mb-0 flex-shrink-0"><i class="bi bi-geo-alt"></i></span>
                            <div>
                                <strong class="d-block">Adresse</strong>
                                <span class="text-muted-tt text-small">Boulevard du 13 Janvier, Lomé, Togo</span>
                            </div>
                        </div>
                        <div class="d-flex gap-3 align-items-start mb-3">
                            <span class="feature-icon mb-0 flex-shrink-0"><i class="bi bi-envelope"></i></span>
                            <div>
                                <strong class="d-block">Email</strong>
                                <span class="text-muted-tt text-small">contact@traveltogo.tg</span>
                            </div>
                        </div>
                        <div class="d-flex gap-3 align-items-start">
                            <span class="feature-icon mb-0 flex-shrink-0"><i class="bi bi-telephone"></i></span>
                            <div>
                                <strong class="d-block">Téléphone</strong>
                                <span class="text-muted-tt text-small">+228 90 00 00 00 · du lundi au vendredi, 8 h – 18 h</span>
                            </div>
                        </div>
                    </div>

                    <div class="sidebar-card" style="background: var(--tt-sand);">
                        <h2 class="h5">Vous préférez réserver ?</h2>
                        <p class="text-muted-tt text-small">Consultez directement les excursions disponibles et envoyez votre demande en ligne.</p>
                        <a href="{{ route('excursions.index') }}" class="btn btn-tt-green w-100">
                            <i class="bi bi-compass me-2"></i>Voir les excursions
                        </a>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="panel">
                        <div class="panel-header">
                            <h2>Envoyer un message</h2>
                        </div>
                        <div class="panel-body p-4">
                            <form method="POST" action="{{ route('contact.store') }}">
                                @csrf

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label" for="nom">Votre nom</label>
                                        <input type="text"
                                               class="form-control @error('nom') is-invalid @enderror"
                                               id="nom"
                                               name="nom"
                                               value="{{ old('nom', auth()->user()?->nom) }}"
                                               maxlength="100"
                                               required>
                                        @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="email">Votre email</label>
                                        <input type="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               id="email"
                                               name="email"
                                               value="{{ old('email', auth()->user()?->email) }}"
                                               maxlength="150"
                                               required>
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <label class="form-label" for="sujet">Sujet</label>
                                    <input type="text"
                                           class="form-control @error('sujet') is-invalid @enderror"
                                           id="sujet"
                                           name="sujet"
                                           value="{{ old('sujet') }}"
                                           maxlength="150"
                                           placeholder="Ex. Demande d’information sur une excursion"
                                           required>
                                    @error('sujet')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mt-3">
                                    <label class="form-label" for="message">Votre message</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror"
                                              id="message"
                                              name="message"
                                              rows="6"
                                              maxlength="3000"
                                              required>{{ old('message') }}</textarea>
                                    @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <div class="form-text">Décrivez votre projet, vos dates et le nombre de personnes.</div>
                                </div>

                                <button type="submit" class="btn btn-tt-green btn-lg mt-4">
                                    <i class="bi bi-send me-2"></i>Envoyer le message
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
