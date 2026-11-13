<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable(['chemin', 'legende', 'id_destination'])]
class Photo extends Model
{
    protected $table = 'photos';

    protected $primaryKey = 'id_photo';

    public $timestamps = false;

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class, 'id_destination', 'id_destination');
    }

    /**
     * Les photos de démonstration sont des URL distantes ; les photos
     * téléversées sont stockées sur le disque public.
     */
    public function getUrlAttribute(): string
    {
        if (Str::startsWith($this->chemin, ['http://', 'https://'])) {
            return $this->chemin;
        }

        return Storage::disk('public')->url($this->chemin);
    }

    public function isDistante(): bool
    {
        return Str::startsWith($this->chemin, ['http://', 'https://']);
    }
}
