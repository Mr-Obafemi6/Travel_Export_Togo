@extends('layouts.app')

@section('title', 'Page introuvable — Travel Togo')

@section('content')
    <section class="auth-wrap">
        <div class="container">
            <div class="panel mx-auto p-4 p-lg-5 text-center" style="max-width: 44rem;">
                <span class="eyebrow justify-content-center">Erreur 404</span>
                <h1 class="section-title">Cette page s’est perdue en voyage…</h1>
                <p class="text-muted-tt">Le lien est peut-être incorrect ou la page a été déplacée. Reprenons le chemin ensemble.</p>
                <div class="d-flex flex-wrap justify-content-center gap-2 mt-4">
                    <a href="{{ route('home') }}" class="btn btn-tt-green"><i class="bi bi-house me-1"></i>Retour à l’accueil</a>
                    <a href="{{ route('destinations.index') }}" class="btn btn-tt-outline-green">Voir les destinations</a>
                </div>
            </div>
        </div>
    </section>
@endsection
