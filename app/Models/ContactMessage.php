<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['nom', 'email', 'sujet', 'message', 'lu', 'id_utilisateur'])]
class ContactMessage extends Model
{
    protected $table = 'contact_messages';

    protected function casts(): array
    {
        return [
            'lu' => 'boolean',
        ];
    }

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur', 'id_utilisateur');
    }
}
