<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PiezaLaborSnapshot extends Model
{
    protected $fillable = [
        'pieza_snapshot_id',
        'labor_id',
        'labor_nombre',
        'labor_referencia',
        'labor_unidad_medida',
        'cantidad',
        'usa_factor_altura',
        'factor_altura_aplicado',
        'precio_unitario',
        'coste_total',
    ];

    protected $casts = [
        'cantidad' => 'decimal:4',
        'usa_factor_altura' => 'boolean',
        'factor_altura_aplicado' => 'decimal:4',
        'precio_unitario' => 'decimal:4',
        'coste_total' => 'decimal:2',
    ];

    public function piezaSnapshot(): BelongsTo
    {
        return $this->belongsTo(PiezaSnapshot::class);
    }

    public function labor(): BelongsTo
    {
        return $this->belongsTo(Labor::class);
    }
}
