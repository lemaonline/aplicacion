<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ZonaSnapshot extends Model
{
    protected $fillable = [
        'presupuesto_snapshot_id',
        'zona_id',
        'nombre',
        'altura_sistema',
        'cerradura',
        'bisagra',
        'm2',
        'num_trasteros',
        'wp_500',
        'wp_250',
        'chapa_galva',
        'puerta_1000',
        'puerta_750',
        'puerta_1500',
        'puerta_2000',
        'twin_750',
        'twin_1000',
        'malla_techo',
        'tablero',
        'esquinas',
        'extra_galva',
        'extra_wp',
        'extra_damero',
        'pasillos',
        'total_coste_materiales',
        'total_coste_fabricacion',
        'total_coste_zona',
    ];

    protected $casts = [
        'pasillos' => 'array',
        'altura_sistema' => 'decimal:2',
        'm2' => 'decimal:2',
        'wp_500' => 'decimal:2',
        'wp_250' => 'decimal:2',
        'chapa_galva' => 'decimal:2',
        'malla_techo' => 'decimal:2',
        'tablero' => 'decimal:2',
        'esquinas' => 'decimal:2',
        'extra_galva' => 'decimal:2',
        'extra_wp' => 'decimal:2',
        'extra_damero' => 'decimal:2',
        'total_coste_materiales' => 'decimal:2',
        'total_coste_fabricacion' => 'decimal:2',
        'total_coste_zona' => 'decimal:2',
    ];

    public function presupuestoSnapshot(): BelongsTo
    {
        return $this->belongsTo(PresupuestoSnapshot::class);
    }

    public function zona(): BelongsTo
    {
        return $this->belongsTo(Zona::class);
    }

    public function piezas(): HasMany
    {
        return $this->hasMany(PiezaSnapshot::class);
    }
}
