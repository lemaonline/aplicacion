<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ZonaCalculo extends Model
{
    protected $fillable = [
        'zona_id',
        'pieza_id',
        'cantidad_total',
        'factor_escala',
        'precio_unitario_snapshot',
        'coste_material',
        'coste_labor',
        'coste_total',
        'desglose_calculo',
    ];

    protected $casts = [
        'cantidad_total' => 'decimal:4',
        'factor_escala' => 'decimal:4',
        'precio_unitario_snapshot' => 'decimal:4',
        'coste_material' => 'decimal:4',
        'coste_labor' => 'decimal:4',
        'coste_total' => 'decimal:4',
    ];

    public function zona(): BelongsTo
    {
        return $this->belongsTo(Zona::class);
    }

    public function pieza(): BelongsTo
    {
        return $this->belongsTo(Pieza::class);
    }
}
