<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    protected $fillable = [
        'presupuesto_id',
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
}
