@extends('layouts.app')

@section('title', 'Mot de passe oublié — Travel Togo')

@section('content')
    <section class="auth-wrap">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-5">
                    <div class="auth-card">
                        <span class="eyebrow">Réinitialisation</span>
                        <h1 class="h3 mt-2 mb-1">Mot de passe oublié ?</h1>
                        <p class="text-muted-tt mb-4">Indiquez votre adresse email : nous vous enverrons un lien pour choisir un nouveau mot de passe.</p>

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="email">Adresse email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <button type="submit" class="btn btn-tt-green w-100">Envoyer le lien</button>
                        </form>

                        <p class="text-center text-muted-tt mt-4 mb-0">
                            <a href="{{ route('login') }}"><i class="bi bi-arrow-left me-1"></i>Retour à la connexion</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
