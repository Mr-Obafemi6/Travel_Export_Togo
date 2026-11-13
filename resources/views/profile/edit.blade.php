@extends('layouts.app')

@section('title', 'Mon profil — Travel Togo')

@section('content')
    <header class="page-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Mon profil</li>
                </ol>
            </nav>
            <span class="eyebrow text-gold mt-3">Espace voyageur</span>
            <h1>Mon profil</h1>
            <p>Gérez vos informations personnelles et la sécurité de votre compte.</p>
        </div>
    </header>

    <section class="section-sm">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="sidebar-card mb-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="avatar-circle" style="width: 3.5rem; height: 3.5rem; font-size: 1.4rem;">
                                {{ mb_strtoupper(mb_substr($utilisateur->nom, 0, 1)) }}
                            </span>
                            <div>
                                <h2 class="h5 mb-0">{{ $utilisateur->nom }}</h2>
                                <span class="text-small text-muted-tt">{{ $utilisateur->email }}</span>
                            </div>
                        </div>
                        <div class="info-line">
                            <span>Rôle</span>
                            <span>{{ $utilisateur->isAdmin() ? 'Administrateur' : 'Touriste' }}</span>
                        </div>
                        <div class="info-line">
                            <span>Membre depuis</span>
                            <span>{{ $utilisateur->date_inscription?->translatedFormat('j F Y') }}</span>
                        </div>
                        <div class="info-line">
                            <span>Réservations</span>
                            <span>{{ $utilisateur->reservations()->count() }}</span>
                        </div>
                        <a href="{{ route('reservations.index') }}" class="btn btn-tt-outline-green w-100 mt-3">
                            <i class="bi bi-journal-check me-2"></i>Mes réservations
                        </a>
                    </div>

                    @if($utilisateur->isAdmin())
                        <div class="sidebar-card" style="background: var(--tt-sand);">
                            <h2 class="h6">Administration</h2>
                            <p class="text-small text-muted-tt">Vous disposez d’un accès à l’espace d’administration de la plateforme.</p>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-tt-green w-100">
                                <i class="bi bi-speedometer2 me-2"></i>Tableau de bord
                            </a>
                        </div>
                    @endif
                </div>

                <div class="col-lg-8">
                    <div class="panel mb-4">
                        <div class="panel-header">
                            <h2>Informations personnelles</h2>
                        </div>
                        <div class="panel-body">
                            <form method="POST" action="{{ route('profile.update') }}">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label class="form-label" for="nom">Nom complet</label>
                                    <input type="text"
                                           class="form-control @error('nom') is-invalid @enderror"
                                           id="nom"
                                           name="nom"
                                           value="{{ old('nom', $utilisateur->nom) }}"
                                           maxlength="100"
                                           required>
                                    @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="email">Adresse email</label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email', $utilisateur->email) }}"
                                           maxlength="150"
                                           required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <button type="submit" class="btn btn-tt-green">
                                    <i class="bi bi-check2 me-2"></i>Enregistrer les modifications
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="panel">
                        <div class="panel-header">
                            <h2>Modifier le mot de passe</h2>
                        </div>
                        <div class="panel-body">
                            <form method="POST" action="{{ route('profile.password') }}">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label class="form-label" for="current_password">Mot de passe actuel</label>
                                    <input type="password"
                                           class="form-control @error('current_password') is-invalid @enderror"
                                           id="current_password"
                                           name="current_password"
                                           autocomplete="current-password"
                                           required>
                                    @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label" for="password">Nouveau mot de passe</label>
                                        <input type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               id="password"
                                               name="password"
                                               autocomplete="new-password"
                                               required>
                                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="password_confirmation">Confirmer le mot de passe</label>
                                        <input type="password"
                                               class="form-control"
                                               id="password_confirmation"
                                               name="password_confirmation"
                                               autocomplete="new-password"
                                               required>
                                    </div>
                                </div>

                                <p class="form-text mt-2">Au moins 8 caractères, avec des lettres et des chiffres.</p>

                                <button type="submit" class="btn btn-tt-outline-green">
                                    <i class="bi bi-shield-lock me-2"></i>Mettre à jour le mot de passe
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
