<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Receta extends Model
{
    protected $fillable = [
        'campo_nombre',
    ];

    protected $casts = [];

    public function items(): HasMany
    {
        return $this->hasMany(RecetaItem::class);
    }

    public function zonas(): HasMany
    {
        return $this->hasMany(Zona::class);
    }
}
