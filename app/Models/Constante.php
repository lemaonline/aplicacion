<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Constante extends Model
{
    protected $fillable = [
        'nombre',
        'valor',
        'descripcion',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
    ];
}
