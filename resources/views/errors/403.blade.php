@extends('layouts.app')

@section('title', 'Accès refusé — Travel Togo')

@section('content')
    <section class="auth-wrap">
        <div class="container">
            <div class="panel mx-auto p-4 p-lg-5 text-center" style="max-width: 44rem;">
                <span class="eyebrow justify-content-center">Erreur 403</span>
                <h1 class="section-title">Accès non autorisé</h1>
                <p class="text-muted-tt">Vous n’avez pas les droits nécessaires pour consulter cette page.</p>
                <div class="d-flex flex-wrap justify-content-center gap-2 mt-4">
                    <a href="{{ route('home') }}" class="btn btn-tt-green"><i class="bi bi-house me-1"></i>Retour à l’accueil</a>
                    <a href="{{ route('contact.create') }}" class="btn btn-tt-outline-green">Nous contacter</a>
                </div>
            </div>
        </div>
    </section>
@endsection
