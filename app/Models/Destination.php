<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nom_destination', 'description', 'latitude', 'longitude', 'id_region'])]
class Destination extends Model
{
    use HasFactory;

    protected $table = 'destinations';

    protected $primaryKey = 'id_destination';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:6',
            'longitude' => 'decimal:6',
        ];
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'id_region', 'id_region');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class, 'id_destination', 'id_destination');
    }

    public function excursions(): HasMany
    {
        return $this->hasMany(Excursion::class, 'id_destination', 'id_destination');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Categorie::class,
            'categorie_destination',
            'id_destination',
            'id_categorie',
            'id_destination',
            'id_categorie'
        );
    }

    public function getPhotoPrincipaleAttribute(): ?Photo
    {
        return $this->photos->first();
    }
}
