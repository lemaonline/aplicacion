<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MontajeSnapshot extends Model
{
    protected $fillable = [
        'presupuesto_snapshot_id',
        'montaje_id',
        'numero_trasteros',
        'superficie_m2',
        'numero_transportes',
        'importe_unidad_transporte',
        'numero_trabajadores',
        'dias_previstos_montaje',
        'dietas',
        'hospedaje',
        'metodo_calculo',
        'precio_por_trastero',
        'precio_hora_instalacion',
        'dieta_trabajador_dia',
        'hospedaje_trabajador_dia',
        'coste_trasteros',
        'coste_mano_obra',
        'coste_transporte',
        'coste_dietas',
        'coste_hospedaje',
        'coste_total_montaje',
        'desglose_calculo',
    ];

    protected $casts = [
        'superficie_m2' => 'decimal:2',
        'importe_unidad_transporte' => 'decimal:2',
        'dietas' => 'boolean',
        'hospedaje' => 'boolean',
        'precio_por_trastero' => 'decimal:2',
        'precio_hora_instalacion' => 'decimal:2',
        'dieta_trabajador_dia' => 'decimal:2',
        'hospedaje_trabajador_dia' => 'decimal:2',
        'coste_trasteros' => 'decimal:2',
        'coste_mano_obra' => 'decimal:2',
        'coste_transporte' => 'decimal:2',
        'coste_dietas' => 'decimal:2',
        'coste_hospedaje' => 'decimal:2',
        'coste_total_montaje' => 'decimal:2',
    ];

    public function presupuestoSnapshot(): BelongsTo
    {
        return $this->belongsTo(PresupuestoSnapshot::class);
    }

    public function montaje(): BelongsTo
    {
        return $this->belongsTo(Montaje::class);
    }
}
