<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[Fillable(['nom', 'email', 'mot_de_passe', 'role', 'est_bloque'])]
#[Hidden(['mot_de_passe'])]
class Utilisateur extends Authenticatable
{
    use HasFactory;

    public const ROLE_TOURISTE = 'touriste';

    public const ROLE_ADMINISTRATEUR = 'administrateur';

    protected $table = 'utilisateurs';

    protected $primaryKey = 'id_utilisateur';

    public const CREATED_AT = 'date_inscription';

    public const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'mot_de_passe' => 'hashed',
            'est_bloque' => 'boolean',
            'date_inscription' => 'datetime',
        ];
    }

    /**
     * Le mot de passe haché est stocké dans mot_de_passe (et non password).
     */
    public function getAuthPassword(): string
    {
        return $this->mot_de_passe;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMINISTRATEUR;
    }

    public function isTouriste(): bool
    {
        return $this->role === self::ROLE_TOURISTE;
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'id_utilisateur', 'id_utilisateur');
    }
}
