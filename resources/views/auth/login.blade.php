@extends('layouts.app')

@section('title', 'Connexion — Travel Togo')

@section('content')
    <section class="auth-wrap">
        <div class="container">
            <div class="row g-4 align-items-stretch justify-content-center">
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="auth-side">
                        <span class="eyebrow text-gold">Bon retour</span>
                        <h2 class="section-title h2">Retrouvez vos aventures togolaises.</h2>
                        <p class="mb-0">Connectez-vous pour suivre vos demandes de réservation et préparer votre prochain séjour.</p>
                    </div>
                </div>
                <div class="col-md-9 col-lg-5">
                    <div class="auth-card">
                        <span class="eyebrow">Connexion</span>
                        <h1 class="h3 mt-2 mb-1">Heureux de vous revoir</h1>
                        <p class="text-muted-tt mb-4">Accédez à votre espace voyageur.</p>

                        <form method="POST" action="{{ route('login.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="email">Adresse email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Mot de passe</label>
                                    <a href="{{ route('password.request') }}" class="small">Mot de passe oublié ?</a>
                                </div>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="current-password">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <button type="submit" class="btn btn-tt-green w-100 btn-lg">Se connecter</button>
                        </form>

                        <p class="text-center text-muted-tt mt-4 mb-0">
                            Pas encore de compte ?
                            <a href="{{ route('register') }}" class="fw-semibold">Créer un compte</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
