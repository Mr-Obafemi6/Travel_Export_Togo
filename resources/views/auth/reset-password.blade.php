@extends('layouts.app')

@section('title', 'Nouveau mot de passe — Travel Togo')

@section('content')
    <section class="auth-wrap">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-5">
                    <div class="auth-card">
                        <span class="eyebrow">Réinitialisation</span>
                        <h1 class="h3 mt-2 mb-1">Choisir un nouveau mot de passe</h1>
                        <p class="text-muted-tt mb-4">Votre nouveau mot de passe doit contenir au moins 8 caractères, une lettre et un chiffre.</p>

                        <form method="POST" action="{{ route('password.store') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="mb-3">
                                <label class="form-label" for="email">Adresse email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $email) }}" required autocomplete="email">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="password">Nouveau mot de passe</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="new-password">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="password_confirmation">Confirmer le mot de passe</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                            </div>
                            <button type="submit" class="btn btn-tt-green w-100">Réinitialiser mon mot de passe</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
