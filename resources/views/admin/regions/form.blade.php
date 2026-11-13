@extends('layouts.admin')

@section('title', $region->exists ? 'Modifier une région' : 'Nouvelle région')
@section('heading', $region->exists ? 'Modifier une région' : 'Nouvelle région')

@section('content')
    <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">{{ $region->exists ? 'Modifier la région' : 'Créer une région' }}</h1>
            <p class="text-muted-tt mb-0">Une région regroupe plusieurs destinations.</p>
        </div>
        <a href="{{ route('admin.regions.index') }}" class="btn btn-light border">
            <i class="bi bi-arrow-left me-1"></i>Retour
        </a>
    </div>

    <div class="panel" style="max-width: 42rem;">
        <div class="panel-body p-4">
            <form method="POST"
                  action="{{ $region->exists ? route('admin.regions.update', $region) : route('admin.regions.store') }}">
                @csrf
                @if($region->exists)
                    @method('PUT')
                @endif

                <div class="mb-4">
                    <label class="form-label" for="nom_region">Nom de la région</label>
                    <input type="text"
                           class="form-control @error('nom_region') is-invalid @enderror"
                           id="nom_region"
                           name="nom_region"
                           value="{{ old('nom_region', $region->nom_region) }}"
                           maxlength="60"
                           required
                           autofocus>
                    @error('nom_region')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-tt-green">
                    <i class="bi bi-check2 me-1"></i>{{ $region->exists ? 'Enregistrer' : 'Créer la région' }}
                </button>
            </form>
        </div>
    </div>
@endsection
