<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PiezaSnapshot extends Model
{
    protected $fillable = [
        'zona_snapshot_id',
        'pieza_id',
        'pieza_nombre',
        'pieza_referencia',
        'cantidad_total',
        'factor_escala',
        'desglose_calculo',
        'coste_materiales',
        'coste_mano_obra',
        'coste_total',
        'precio_unitario',
    ];

    protected $casts = [
        'cantidad_total' => 'decimal:4',
        'factor_escala' => 'decimal:4',
        'coste_materiales' => 'decimal:2',
        'coste_mano_obra' => 'decimal:2',
        'coste_total' => 'decimal:2',
        'precio_unitario' => 'decimal:4',
    ];

    public function zonaSnapshot(): BelongsTo
    {
        return $this->belongsTo(ZonaSnapshot::class);
    }

    public function pieza(): BelongsTo
    {
        return $this->belongsTo(Pieza::class);
    }

    public function materiales(): HasMany
    {
        return $this->hasMany(PiezaMaterialSnapshot::class);
    }

    public function labores(): HasMany
    {
        return $this->hasMany(PiezaLaborSnapshot::class);
    }
}
