<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Montaje extends Model
{
    protected $table = 'montajes';

    protected $fillable = [
        'presupuesto_id',
        'numero_trasteros',
        'superficie_m2',
        'numero_transportes',
        'importe_unidad_transporte',
        'numero_trabajadores',
        'dias_previstos_montaje',
        'dietas',
        'hospedaje',
    ];

    public function presupuesto(): BelongsTo
    {
        return $this->belongsTo(Presupuesto::class);
    }
}
