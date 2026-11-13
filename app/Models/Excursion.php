<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['titre', 'description', 'prix', 'duree_heures', 'places_max', 'date_excursion', 'id_destination'])]
class Excursion extends Model
{
    use HasFactory;

    protected $table = 'excursions';

    protected $primaryKey = 'id_excursion';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'prix' => 'decimal:2',
            'duree_heures' => 'integer',
            'places_max' => 'integer',
            'date_excursion' => 'datetime',
        ];
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class, 'id_destination', 'id_destination');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'id_excursion', 'id_excursion');
    }

    public function reservationsActives(): HasMany
    {
        return $this->reservations()->whereIn('statut', Reservation::STATUTS_ACTIFS);
    }

    public function scopeAVenir(Builder $query): Builder
    {
        return $query->where('date_excursion', '>', now());
    }

    public function getEstPasseeAttribute(): bool
    {
        return $this->date_excursion->isPast();
    }

    /**
     * Les places restantes ne sont pas stockées : elles se calculent à partir
     * des réservations en attente ou confirmées (RG2).
     */
    public function getPlacesRestantesAttribute(): int
    {
        $prises = $this->personnes_reservees
            ?? $this->reservationsActives()->sum('nb_personnes');

        return max(0, $this->places_max - (int) $prises);
    }

    public function getEstCompleteAttribute(): bool
    {
        return $this->places_restantes <= 0;
    }
}
