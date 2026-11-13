@extends('layouts.admin')

@section('title', 'Message de '.$message->nom)
@section('heading', 'Message de '.$message->nom)

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">{{ $message->sujet }}</h1>
            <p class="text-muted-tt mb-0">
                Reçu le {{ $message->created_at?->translatedFormat('l j F Y à H:i') }}
            </p>
        </div>
        <a href="{{ route('admin.messages.index') }}" class="btn btn-light border">
            <i class="bi bi-arrow-left me-1"></i>Retour
        </a>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="panel">
                <div class="panel-header">
                    <h2>Contenu du message</h2>
                    <span class="badge bg-{{ $message->lu ? 'secondary' : 'warning text-dark' }} badge-status">
                        {{ $message->lu ? 'Lu' : 'Nouveau' }}
                    </span>
                </div>
                <div class="panel-body p-4">
                    <p style="white-space: pre-line;">{{ $message->message }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="panel mb-3">
                <div class="panel-header">
                    <h2>Expéditeur</h2>
                </div>
                <div class="panel-body">
                    <div class="info-line">
                        <span>Nom</span>
                        <span>{{ $message->nom }}</span>
                    </div>
                    <div class="info-line">
                        <span>Email</span>
                        <span><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></span>
                    </div>
                    <div class="info-line">
                        <span>Compte lié</span>
                        <span>{{ $message->utilisateur?->nom ?? 'Visiteur non connecté' }}</span>
                    </div>
                    <div class="info-line">
                        <span>Reçu le</span>
                        <span>{{ $message->created_at?->translatedFormat('j M Y · H:i') }}</span>
                    </div>

                    <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: '.$message->sujet) }}" class="btn btn-tt-green w-100 mt-3">
                        <i class="bi bi-reply me-1"></i>Répondre par email
                    </a>
                </div>
            </div>

            <div class="panel">
                <div class="panel-body">
                    <form method="POST" action="{{ route('admin.messages.destroy', $message) }}"
                          onsubmit="return confirm('Supprimer définitivement ce message ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash me-1"></i>Supprimer le message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
