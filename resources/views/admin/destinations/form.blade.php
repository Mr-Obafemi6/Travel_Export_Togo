@extends('layouts.admin')

@section('title', $destination->exists ? 'Modifier une destination' : 'Nouvelle destination')
@section('heading', $destination->exists ? 'Modifier une destination' : 'Nouvelle destination')

@section('content')
    <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">{{ $destination->exists ? 'Modifier la destination' : 'Créer une destination' }}</h1>
            <p class="text-muted-tt mb-0">Renseignez les informations, les catégories et les photos.</p>
        </div>
        <a href="{{ route('admin.destinations.index') }}" class="btn btn-light border">
            <i class="bi bi-arrow-left me-1"></i>Retour
        </a>
    </div>

    <form method="POST"
          action="{{ $destination->exists ? route('admin.destinations.update', $destination) : route('admin.destinations.store') }}"
          enctype="multipart/form-data">
        @csrf
        @if($destination->exists)
            @method('PUT')
        @endif

        <div class="row g-3">
            <div class="col-lg-8">
                <div class="panel mb-3">
                    <div class="panel-header">
                        <h2>Informations principales</h2>
                    </div>
                    <div class="panel-body">
                        <div class="mb-3">
                            <label class="form-label" for="nom_destination">Nom de la destination</label>
                            <input type="text"
                                   class="form-control @error('nom_destination') is-invalid @enderror"
                                   id="nom_destination"
                                   name="nom_destination"
                                   value="{{ old('nom_destination', $destination->nom_destination) }}"
                                   maxlength="120"
                                   required>
                            @error('nom_destination')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="7"
                                      required>{{ old('description', $destination->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Présentez le lieu, son atmosphère et ce qu’il faut y voir.</div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="latitude">Latitude</label>
                                <input type="number" step="0.000001" min="-90" max="90"
                                       class="form-control @error('latitude') is-invalid @enderror"
                                       id="latitude"
                                       name="latitude"
                                       value="{{ old('latitude', $destination->latitude) }}"
                                       placeholder="6.131944">
                                @error('latitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="longitude">Longitude</label>
                                <input type="number" step="0.000001" min="-180" max="180"
                                       class="form-control @error('longitude') is-invalid @enderror"
                                       id="longitude"
                                       name="longitude"
                                       value="{{ old('longitude', $destination->longitude) }}"
                                       placeholder="1.222778">
                                @error('longitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel mb-3">
                    <div class="panel-header">
                        <h2>Photos</h2>
                        <span class="text-small text-muted-tt">JPG, PNG ou WEBP · 4 Mo max.</span>
                    </div>
                    <div class="panel-body">
                        @if($destination->exists && $destination->photos->isNotEmpty())
                            <div class="row g-3 mb-4">
                                @foreach($destination->photos as $photo)
                                    <div class="col-6 col-md-4">
                                        <div class="border rounded p-2 h-100">
                                            <img src="{{ $photo->url }}" alt="{{ $photo->legende }}" class="w-100 rounded mb-2" style="aspect-ratio: 4 / 3; object-fit: cover;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       name="remove_photos[]"
                                                       value="{{ $photo->id_photo }}"
                                                       id="remove_photo_{{ $photo->id_photo }}">
                                                <label class="form-check-label text-small" for="remove_photo_{{ $photo->id_photo }}">
                                                    Supprimer cette photo
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="mb-2">
                            <label class="form-label" for="photos">Ajouter des photos</label>
                            <input type="file" class="form-control @error('photos.*') is-invalid @enderror"
                                   id="photos" name="photos[]" accept="image/jpeg,image/png,image/webp" multiple>
                            @error('photos.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-2">
                            @for($i = 0; $i < 4; $i++)
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="legendes[]" value="{{ old('legendes.'.$i) }}"
                                           maxlength="150" placeholder="Légende de la photo {{ $i + 1 }} (facultatif)">
                                </div>
                            @endfor
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
                        <div class="mb-3">
                            <label class="form-label" for="id_region">Région</label>
                            <select class="form-select @error('id_region') is-invalid @enderror" id="id_region" name="id_region" required>
                                <option value="">Choisir une région</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region->id_region }}" @selected((int) old('id_region', $destination->id_region) === $region->id_region)>
                                        {{ $region->nom_region }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_region')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-tt-green w-100">
                            <i class="bi bi-check2 me-1"></i>{{ $destination->exists ? 'Enregistrer' : 'Créer la destination' }}
                        </button>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-header">
                        <h2>Catégories</h2>
                    </div>
                    <div class="panel-body">
                        @forelse($categories as $categorie)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox"
                                       name="categories[]"
                                       value="{{ $categorie->id_categorie }}"
                                       id="categorie_{{ $categorie->id_categorie }}"
                                       @checked(in_array($categorie->id_categorie, old('categories', $destination->categories->pluck('id_categorie')->all())))>
                                <label class="form-check-label" for="categorie_{{ $categorie->id_categorie }}">
                                    {{ $categorie->libelle }}
                                </label>
                            </div>
                        @empty
                            <p class="text-muted-tt text-small mb-0">Aucune catégorie disponible.</p>
                        @endforelse
                        @error('categories.*')<div class="text-danger text-small mt-2">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
