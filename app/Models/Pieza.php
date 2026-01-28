<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pieza extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'unidad_medida',
        'tipo_elemento',
    ];

    public function materiales(): BelongsToMany
    {
        return $this->belongsToMany(Material::class, 'pieza_material')
            ->withPivot('cantidad')
            ->withTimestamps();
    }

    public function labores(): BelongsToMany
    {
        return $this->belongsToMany(Labor::class, 'pieza_labor')
            ->withPivot('cantidad')
            ->withTimestamps();
    }

    public function calcularCosteMateriales(): float
    {
        return $this->materiales()
            ->get()
            ->reduce(function ($total, $material) {
                $cantidad = $material->pivot->cantidad;
                $precio = $material->precio;
                return $total + ($cantidad * $precio);
            }, 0);
    }

    public function calcularCosteLabor(): float
    {
        return $this->labores()
            ->get()
            ->reduce(function ($total, $labor) {
                $horas = $labor->pivot->cantidad;
                $precio_hora = $labor->precio_hora;
                return $total + ($horas * $precio_hora);
            }, 0);
    }

    public function calcularCosteTotal(): float
    {
        return $this->calcularCosteMateriales() + $this->calcularCosteLabor();
    }
}
