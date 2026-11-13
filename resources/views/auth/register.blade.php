@extends('layouts.app')

@section('title', 'Créer un compte — Travel Togo')

@section('content')
    <section class="auth-wrap">
        <div class="container">
            <div class="row g-4 align-items-stretch justify-content-center">
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="auth-side">
                        <span class="eyebrow text-gold">Rejoignez-nous</span>
                        <h2 class="section-title h2">Votre voyage commence par un compte.</h2>
                        <p class="mb-0">Réservez vos excursions, suivez vos demandes et gardez toutes vos expériences togolaises au même endroit.</p>
                    </div>
                </div>
                <div class="col-md-10 col-lg-6">
                    <div class="auth-card">
                        <span class="eyebrow">Inscription</span>
                        <h1 class="h3 mt-2 mb-1">Créer mon compte touriste</h1>
                        <p class="text-muted-tt mb-4">C’est gratuit et cela prend moins d’une minute.</p>

                        <form method="POST" action="{{ route('register.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="nom">Nom complet</label>
                                <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom') }}" required autofocus autocomplete="name">
                                @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="email">Adresse email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-sm-6">
                                    <label class="form-label" for="password">Mot de passe</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="new-password">
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="password_confirmation">Confirmation</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>
                            <p class="form-text mb-3">8 caractères minimum, avec au moins une lettre et un chiffre.</p>
                            <button type="submit" class="btn btn-tt w-100 btn-lg">Créer mon compte</button>
                        </form>

                        <p class="text-center text-muted-tt mt-4 mb-0">
                            Vous avez déjà un compte ?
                            <a href="{{ route('login') }}" class="fw-semibold">Se connecter</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
