@extends('layouts.admin')

@section('title', $excursion->exists ? 'Modifier une excursion' : 'Nouvelle excursion')
@section('heading', $excursion->exists ? 'Modifier une excursion' : 'Nouvelle excursion')

@section('content')
    <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">{{ $excursion->exists ? 'Modifier l’excursion' : 'Créer une excursion' }}</h1>
            <p class="text-muted-tt mb-0">Définissez la date, le tarif et le nombre de places.</p>
        </div>
        <a href="{{ route('admin.excursions.index') }}" class="btn btn-light border">
            <i class="bi bi-arrow-left me-1"></i>Retour
        </a>
    </div>

    <form method="POST"
          action="{{ $excursion->exists ? route('admin.excursions.update', $excursion) : route('admin.excursions.store') }}">
        @csrf
        @if($excursion->exists)
            @method('PUT')
        @endif

        <div class="row g-3">
            <div class="col-lg-8">
                <div class="panel">
                    <div class="panel-header">
                        <h2>Détails de l’excursion</h2>
                    </div>
                    <div class="panel-body">
                        <div class="mb-3">
                            <label class="form-label" for="titre">Titre</label>
                            <input type="text"
                                   class="form-control @error('titre') is-invalid @enderror"
                                   id="titre"
                                   name="titre"
                                   value="{{ old('titre', $excursion->titre) }}"
                                   maxlength="150"
                                   required>
                            @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="7"
                                      required>{{ old('description', $excursion->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="id_destination">Destination</label>
                                <select class="form-select @error('id_destination') is-invalid @enderror" id="id_destination" name="id_destination" required>
                                    <option value="">Choisir une destination</option>
                                    @foreach($destinations as $destination)
                                        <option value="{{ $destination->id_destination }}" @selected((int) old('id_destination', $excursion->id_destination) === $destination->id_destination)>
                                            {{ $destination->nom_destination }} — {{ $destination->region->nom_region }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_destination')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="date_excursion">Date et heure de départ</label>
                                <input type="datetime-local"
                                       class="form-control @error('date_excursion') is-invalid @enderror"
                                       id="date_excursion"
                                       name="date_excursion"
                                       value="{{ old('date_excursion', $excursion->date_excursion?->format('Y-m-d\TH:i')) }}"
                                       required>
                                @error('date_excursion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text">La date doit être postérieure à maintenant.</div>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-4">
                                <label class="form-label" for="prix">Prix par personne (FCFA)</label>
                                <input type="number" step="0.01" min="1"
                                       class="form-control @error('prix') is-invalid @enderror"
                                       id="prix"
                                       name="prix"
                                       value="{{ old('prix', $excursion->prix) }}"
                                       required>
                                @error('prix')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="duree_heures">Durée (heures)</label>
                                <input type="number" min="1" max="72"
                                       class="form-control @error('duree_heures') is-invalid @enderror"
                                       id="duree_heures"
                                       name="duree_heures"
                                       value="{{ old('duree_heures', $excursion->duree_heures) }}"
                                       required>
                                @error('duree_heures')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="places_max">Places maximum</label>
                                <input type="number" min="1" max="500"
                                       class="form-control @error('places_max') is-invalid @enderror"
                                       id="places_max"
                                       name="places_max"
                                       value="{{ old('places_max', $excursion->places_max) }}"
                                       required>
                                @error('places_max')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="panel mb-3">
                    <div class="panel-header">
                        <h2>Publication</h2>
                    </div>
                    <div class="panel-body">
                        <button type="submit" class="btn btn-tt-green w-100">
                            <i class="bi bi-check2 me-1"></i>{{ $excursion->exists ? 'Enregistrer' : 'Créer l’excursion' }}
                        </button>
                        <a href="{{ route('admin.excursions.index') }}" class="btn btn-light border w-100 mt-2">Annuler</a>
                    </div>
                </div>

                @if($excursion->exists)
                    <div class="panel">
                        <div class="panel-header">
                            <h2>Disponibilité</h2>
                        </div>
                        <div class="panel-body">
                            <div class="info-line">
                                <span>Places réservées</span>
                                <span>{{ $excursion->reservationsActives()->sum('nb_personnes') }}</span>
                            </div>
                            <div class="info-line">
                                <span>Places restantes</span>
                                <span>{{ $excursion->places_restantes }} / {{ $excursion->places_max }}</span>
                            </div>
                            <div class="info-line">
                                <span>Réservations</span>
                                <span>{{ $excursion->reservations()->count() }}</span>
                            </div>
                            <a href="{{ route('admin.reservations.index', ['q' => $excursion->titre]) }}" class="btn btn-tt-outline-green w-100 mt-3 btn-sm">
                                Voir les réservations
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </form>
@endsection
