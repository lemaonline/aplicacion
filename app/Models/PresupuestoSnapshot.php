<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PresupuestoSnapshot extends Model
{
    protected $fillable = [
        'presupuesto_id',
        'margen_materiales',
        'margen_mano_obra',
        'margen_montaje',
        'comision',
        'total_coste_materiales',
        'total_coste_fabricacion',
        'total_coste_montaje',
        'total_venta',
    ];

    protected $casts = [
        'margen_materiales' => 'decimal:2',
        'margen_mano_obra' => 'decimal:2',
        'margen_montaje' => 'decimal:2',
        'comision' => 'decimal:2',
        'total_coste_materiales' => 'decimal:2',
        'total_coste_fabricacion' => 'decimal:2',
        'total_coste_montaje' => 'decimal:2',
        'total_venta' => 'decimal:2',
    ];

    public function presupuesto(): BelongsTo
    {
        return $this->belongsTo(Presupuesto::class);
    }

    public function zonas(): HasMany
    {
        return $this->hasMany(ZonaSnapshot::class);
    }

    public function montaje(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MontajeSnapshot::class);
    }
}
