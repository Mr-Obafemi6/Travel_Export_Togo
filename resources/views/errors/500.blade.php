@extends('layouts.app')

@section('title', 'Erreur serveur — Travel Togo')

@section('content')
    <section class="auth-wrap">
        <div class="container">
            <div class="panel mx-auto p-4 p-lg-5 text-center" style="max-width: 44rem;">
                <span class="eyebrow justify-content-center">Erreur 500</span>
                <h1 class="section-title">Un imprévu sur la route</h1>
                <p class="text-muted-tt">Une erreur inattendue s’est produite. Notre équipe en est informée, merci de réessayer dans un instant.</p>
                <div class="d-flex flex-wrap justify-content-center gap-2 mt-4">
                    <a href="{{ route('home') }}" class="btn btn-tt-green"><i class="bi bi-house me-1"></i>Retour à l’accueil</a>
                    <a href="{{ route('contact.create') }}" class="btn btn-tt-outline-green">Signaler le problème</a>
                </div>
            </div>
        </div>
    </section>
@endsection
