<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Material extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'unidad_medida',
        'stock_actual',
        'stock_minimo',
        'precio',
    ];

    protected $casts = [
        'stock_actual' => 'decimal:2',
        'stock_minimo' => 'decimal:2',
        'precio' => 'decimal:2',
    ];

    public function piezas(): BelongsToMany
    {
        return $this->belongsToMany(Pieza::class, 'pieza_material')
            ->withPivot('cantidad')
            ->withTimestamps();
    }
}
