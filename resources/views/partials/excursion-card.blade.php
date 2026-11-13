@php
    $photo = $excursion->destination->photo_principale;
    $restantes = $excursion->places_restantes;
    $pourcentage = $excursion->places_max > 0 ? min(100, round($restantes / $excursion->places_max * 100)) : 0;
@endphp
<article class="card-tt excursion-card">
    <a href="{{ route('excursions.show', $excursion) }}" class="d-block text-decoration-none">
        <div class="card-media">
            @if($photo)
                <img src="{{ $photo->url }}" alt="{{ $excursion->destination->nom_destination }}" loading="lazy">
            @else
                <div class="d-flex align-items-center justify-content-center h-100 bg-sand text-tt-green fs-1">
                    <i class="bi bi-sun"></i>
                </div>
            @endif
            <span class="badge-region">{{ $excursion->destination->region->nom_region }}</span>
            <span class="price-tag">{{ number_format($excursion->prix, 0, ',', ' ') }} FCFA</span>
        </div>
    </a>
    <div class="card-body-tt">
        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
            <span class="date-chip"><i class="bi bi-calendar-event"></i>{{ $excursion->date_excursion->translatedFormat('j M Y') }}</span>
            <span class="text-small text-muted-tt"><i class="bi bi-clock me-1"></i>{{ $excursion->duree_heures }}h</span>
        </div>
        <h3 class="card-title-tt h5 mb-2">
            <a href="{{ route('excursions.show', $excursion) }}" class="stretched-link text-reset">{{ $excursion->titre }}</a>
        </h3>
        <p class="text-small text-muted-tt mb-3">
            <i class="bi bi-geo-alt me-1"></i>{{ $excursion->destination->nom_destination }}
        </p>
        <div class="d-flex justify-content-between text-small text-muted-tt mb-1">
            <span>{{ $restantes }} place(s) restante(s)</span>
            <span>{{ $excursion->places_max }} au total</span>
        </div>
        <div class="progress" role="progressbar" aria-label="Places restantes" aria-valuenow="{{ $pourcentage }}" aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar" style="width: {{ $pourcentage }}%"></div>
        </div>
    </div>
</article>
