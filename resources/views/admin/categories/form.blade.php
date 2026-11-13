@extends('layouts.admin')

@section('title', $categorie->exists ? 'Modifier une catégorie' : 'Nouvelle catégorie')
@section('heading', $categorie->exists ? 'Modifier une catégorie' : 'Nouvelle catégorie')

@section('content')
    <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">{{ $categorie->exists ? 'Modifier la catégorie' : 'Créer une catégorie' }}</h1>
            <p class="text-muted-tt mb-0">Les catégories sont communes à toutes les régions.</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-light border">
            <i class="bi bi-arrow-left me-1"></i>Retour
        </a>
    </div>

    <div class="panel" style="max-width: 42rem;">
        <div class="panel-body p-4">
            <form method="POST"
                  action="{{ $categorie->exists ? route('admin.categories.update', $categorie) : route('admin.categories.store') }}">
                @csrf
                @if($categorie->exists)
                    @method('PUT')
                @endif

                <div class="mb-4">
                    <label class="form-label" for="libelle">Libellé</label>
                    <input type="text"
                           class="form-control @error('libelle') is-invalid @enderror"
                           id="libelle"
                           name="libelle"
                           value="{{ old('libelle', $categorie->libelle) }}"
                           maxlength="60"
                           required
                           autofocus>
                    @error('libelle')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-tt-green">
                    <i class="bi bi-check2 me-1"></i>{{ $categorie->exists ? 'Enregistrer' : 'Créer la catégorie' }}
                </button>
            </form>
        </div>
    </div>
@endsection
