<?php

namespace App\Models;

use App\Services\CalculoRecetaService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Zona extends Model
{
    protected static function booted()
    {
        static::saved(function ($zona) {
            // Solo recalcular si no estamos ya en una transacción de cálculo
            // o si queremos forzar el recálculo siempre que cambien datos
            app(CalculoRecetaService::class)->calcular($zona);
        });
    }

    protected $fillable = [
        'presupuesto_id',
        'receta_id',
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
        'pasillos',
        'malla_techo',
        'tablero',
        'esquinas',
        'extra_galva',
        'extra_wp',
        'extra_damero',
    ];

    protected $casts = [
        'pasillos' => 'array',
    ];

    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class);
    }

    public function receta()
    {
        return $this->belongsTo(Receta::class);
    }

    public function calculos()
    {
        return $this->hasMany(ZonaCalculo::class);
    }
}
