<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Labor extends Model
{
    protected $fillable = [
        'nombre',
        'precio_hora',
    ];

    protected $casts = [
        'precio_hora' => 'decimal:2',
    ];

    public function piezas(): BelongsToMany
    {
        return $this->belongsToMany(Pieza::class, 'pieza_labor')
            ->withPivot('cantidad')
            ->withTimestamps();
    }
}
