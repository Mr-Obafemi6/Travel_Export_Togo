<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['nb_personnes', 'montant', 'statut', 'id_utilisateur', 'id_excursion'])]
class Reservation extends Model
{
    use HasFactory;

    public const STATUT_EN_ATTENTE = 'en_attente';

    public const STATUT_CONFIRMEE = 'confirmee';

    public const STATUT_ANNULEE = 'annulee';

    public const STATUT_TERMINEE = 'terminee';

    public const STATUTS_ACTIFS = [self::STATUT_EN_ATTENTE, self::STATUT_CONFIRMEE];

    public const STATUTS = [
        self::STATUT_EN_ATTENTE,
        self::STATUT_CONFIRMEE,
        self::STATUT_ANNULEE,
        self::STATUT_TERMINEE,
    ];

    protected $table = 'reservations';

    protected $primaryKey = 'id_reservation';

    public const CREATED_AT = 'date_reservation';

    public const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'nb_personnes' => 'integer',
            'montant' => 'decimal:2',
            'date_reservation' => 'datetime',
        ];
    }

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur', 'id_utilisateur');
    }

    public function excursion(): BelongsTo
    {
        return $this->belongsTo(Excursion::class, 'id_excursion', 'id_excursion');
    }

    public function scopeActives(Builder $query): Builder
    {
        return $query->whereIn('statut', self::STATUTS_ACTIFS);
    }

    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            self::STATUT_EN_ATTENTE => 'En attente',
            self::STATUT_CONFIRMEE => 'Confirmée',
            self::STATUT_ANNULEE => 'Annulée',
            self::STATUT_TERMINEE => 'Terminée',
            default => ucfirst($this->statut),
        };
    }

    public function getStatutBadgeAttribute(): string
    {
        return match ($this->statut) {
            self::STATUT_EN_ATTENTE => 'warning',
            self::STATUT_CONFIRMEE => 'success',
            self::STATUT_ANNULEE => 'danger',
            self::STATUT_TERMINEE => 'secondary',
            default => 'secondary',
        };
    }

    /**
     * RG5 : un touriste peut annuler jusqu'à 48 heures avant l'excursion.
     */
    public function getPeutEtreAnnuleeAttribute(): bool
    {
        if (! in_array($this->statut, self::STATUTS_ACTIFS, true)) {
            return false;
        }

        return $this->excursion !== null
            && $this->excursion->date_excursion->greaterThan(now()->addHours(48));
    }
}
