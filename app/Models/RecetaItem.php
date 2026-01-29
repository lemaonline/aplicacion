<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecetaItem extends Model
{
    protected $fillable = [
        'receta_id',
        'pieza_id',
        'cantidad_base',
        'referencia',
        'formula',
        'condicion_cerradura',
        'condicion_bisagra',
    ];

    protected $casts = [
        'cantidad_base' => 'decimal:4',
    ];

    public function receta(): BelongsTo
    {
        return $this->belongsTo(Receta::class);
    }

    public function pieza(): BelongsTo
    {
        return $this->belongsTo(Pieza::class);
    }
}
