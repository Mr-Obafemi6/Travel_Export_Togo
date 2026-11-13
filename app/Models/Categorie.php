<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['libelle'])]
class Categorie extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $primaryKey = 'id_categorie';

    public $timestamps = false;

    public function destinations(): BelongsToMany
    {
        return $this->belongsToMany(
            Destination::class,
            'categorie_destination',
            'id_categorie',
            'id_destination',
            'id_categorie',
            'id_destination'
        );
    }
}
