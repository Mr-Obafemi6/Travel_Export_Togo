@extends('layouts.app')

@section('title', 'Session expirée — Travel Togo')

@section('content')
    <section class="auth-wrap">
        <div class="container">
            <div class="panel mx-auto p-4 p-lg-5 text-center" style="max-width: 44rem;">
                <span class="eyebrow justify-content-center">Erreur 419</span>
                <h1 class="section-title">Votre session a expiré</h1>
                <p class="text-muted-tt">Pour votre sécurité, le formulaire n’est plus valide. Actualisez la page puis réessayez.</p>
                <div class="d-flex flex-wrap justify-content-center gap-2 mt-4">
                    <a href="{{ url()->previous() }}" class="btn btn-tt-green">Revenir en arrière</a>
                    <a href="{{ route('home') }}" class="btn btn-tt-outline-green">Retour à l’accueil</a>
                </div>
            </div>
        </div>
    </section>
@endsection
