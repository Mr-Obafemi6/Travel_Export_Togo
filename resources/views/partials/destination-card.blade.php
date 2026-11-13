@php
    $photo = $destination->photo_principale;
@endphp
<article class="card-tt">
    <a href="{{ route('destinations.show', $destination) }}" class="d-block text-decoration-none">
        <div class="card-media">
            @if($photo)
                <img src="{{ $photo->url }}" alt="{{ $photo->legende ?: $destination->nom_destination }}" loading="lazy">
            @else
                <div class="d-flex align-items-center justify-content-center h-100 bg-sand text-tt-green fs-1">
                    <i class="bi bi-geo-alt"></i>
                </div>
            @endif
            <span class="badge-region">{{ $destination->region->nom_region }}</span>
        </div>
    </a>
    <div class="card-body-tt">
        @if($destination->categories->isNotEmpty())
            <div class="d-flex flex-wrap gap-1 mb-2">
                @foreach($destination->categories->take(2) as $categorie)
                    <span class="badge-soft">{{ $categorie->libelle }}</span>
                @endforeach
            </div>
        @endif
        <h3 class="card-title-tt">
            <a href="{{ route('destinations.show', $destination) }}" class="stretched-link text-reset">{{ $destination->nom_destination }}</a>
        </h3>
        <p class="card-text-tt">{{ \Illuminate\Support\Str::limit(strip_tags($destination->description), 105) }}</p>
        <div class="card-meta">
            <span><i class="bi bi-signpost-split me-1"></i>{{ $destination->excursions_count ?? $destination->excursions->count() }} excursion(s)</span>
            <span><i class="bi bi-compass me-1"></i>Découvrir</span>
        </div>
    </div>
</article>
