<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PiezaMaterialSnapshot extends Model
{
    protected $fillable = [
        'pieza_snapshot_id',
        'material_id',
        'material_nombre',
        'material_referencia',
        'material_unidad_medida',
        'cantidad',
        'es_proporcional',
        'usa_factor_altura',
        'factor_altura_aplicado',
        'precio_unitario',
        'coste_total',
    ];

    protected $casts = [
        'cantidad' => 'decimal:4',
        'es_proporcional' => 'boolean',
        'usa_factor_altura' => 'boolean',
        'factor_altura_aplicado' => 'decimal:4',
        'precio_unitario' => 'decimal:4',
        'coste_total' => 'decimal:2',
    ];

    public function piezaSnapshot(): BelongsTo
    {
        return $this->belongsTo(PiezaSnapshot::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
