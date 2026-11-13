<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nom_region'])]
class Region extends Model
{
    use HasFactory;

    protected $table = 'regions';

    protected $primaryKey = 'id_region';

    public $timestamps = false;

    public function destinations(): HasMany
    {
        return $this->hasMany(Destination::class, 'id_region', 'id_region');
    }
}
