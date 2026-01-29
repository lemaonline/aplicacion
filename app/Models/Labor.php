<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Labor extends Model
{
    protected $fillable = [
        'nombre',
        'unidad_medida',
        'precio',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
    ];

    public function piezas(): BelongsToMany
    {
        return $this->belongsToMany(Pieza::class, 'pieza_labor')
            ->withPivot('cantidad')
            ->withTimestamps();
    }
}
