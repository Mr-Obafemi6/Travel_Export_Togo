@extends('layouts.admin')

@section('title', 'Utilisateurs')
@section('heading', 'Utilisateurs')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Utilisateurs</h1>
            <p class="text-muted-tt mb-0">Consultez les comptes et bloquez les accès en cas de besoin.</p>
        </div>
    </div>

    <form class="panel p-3 mb-3" method="GET" action="{{ route('admin.utilisateurs.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label" for="q">Recherche</label>
                <input type="search" class="form-control" id="q" name="q" value="{{ $recherche }}" placeholder="Nom ou email…">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="role">Rôle</label>
                <select class="form-select" id="role" name="role">
                    <option value="">Tous les rôles</option>
                    <option value="{{ \App\Models\Utilisateur::ROLE_TOURISTE }}" @selected($role === \App\Models\Utilisateur::ROLE_TOURISTE)>Touristes</option>
                    <option value="{{ \App\Models\Utilisateur::ROLE_ADMINISTRATEUR }}" @selected($role === \App\Models\Utilisateur::ROLE_ADMINISTRATEUR)>Administrateurs</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-tt-green flex-grow-1"><i class="bi bi-funnel me-1"></i>Filtrer</button>
                <a href="{{ route('admin.utilisateurs.index') }}" class="btn btn-light border" title="Réinitialiser"><i class="bi bi-x-lg"></i></a>
            </div>
        </div>
    </form>

    <div class="panel">
        <div class="table-responsive">
            <table class="table table-tt align-middle">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Rôle</th>
                        <th>Inscription</th>
                        <th>Réservations</th>
                        <th>État</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($utilisateurs as $utilisateur)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="avatar-circle">{{ mb_strtoupper(mb_substr($utilisateur->nom, 0, 1)) }}</span>
                                    <div>
                                        <div class="fw-semibold">{{ $utilisateur->nom }}</div>
                                        <div class="text-small text-muted-tt">{{ $utilisateur->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($utilisateur->isAdmin())
                                    <span class="badge bg-dark badge-status">Administrateur</span>
                                @else
                                    <span class="badge bg-success badge-status">Touriste</span>
                                @endif
                            </td>
                            <td class="text-small">{{ $utilisateur->date_inscription?->translatedFormat('j M Y') }}</td>
                            <td><span class="badge-soft">{{ $utilisateur->reservations_count }}</span></td>
                            <td>
                                @if($utilisateur->est_bloque)
                                    <span class="badge bg-danger badge-status">Bloqué</span>
                                @else
                                    <span class="badge bg-success badge-status">Actif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($utilisateur->id_utilisateur === auth()->id())
                                    <span class="text-small text-muted-tt">Votre compte</span>
                                @elseif($utilisateur->isAdmin())
                                    <span class="text-small text-muted-tt">Protégé</span>
                                @else
                                    <form method="POST" action="{{ route('admin.utilisateurs.blocage', $utilisateur) }}"
                                          onsubmit="return confirm('{{ $utilisateur->est_bloque ? 'Réactiver' : 'Bloquer' }} le compte de {{ $utilisateur->nom }} ?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $utilisateur->est_bloque ? 'btn-tt-outline-green' : 'btn-outline-danger' }}">
                                            <i class="bi bi-{{ $utilisateur->est_bloque ? 'unlock' : 'lock' }} me-1"></i>
                                            {{ $utilisateur->est_bloque ? 'Réactiver' : 'Bloquer' }}
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted-tt py-4">Aucun utilisateur trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $utilisateurs->links() }}</div>
@endsection
