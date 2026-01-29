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
            ->withPivot('cantidad', 'es_proporcional')
            ->withTimestamps();
    }

    public function labores(): BelongsToMany
    {
        return $this->belongsToMany(Labor::class, 'pieza_labor')
            ->withPivot('cantidad')
            ->withTimestamps();
    }

    public function calcularCosteMaterialesProporcionales(): float
    {
        return $this->materiales()
            ->wherePivot('es_proporcional', true)
            ->get()
            ->reduce(function ($total, $material) {
                return $total + ($material->pivot->cantidad * $material->precio);
            }, 0);
    }

    public function calcularCosteMaterialesFijos(): float
    {
        return $this->materiales()
            ->wherePivot('es_proporcional', false)
            ->get()
            ->reduce(function ($total, $material) {
                return $total + ($material->pivot->cantidad * $material->precio);
            }, 0);
    }

    public function calcularCosteMateriales(): float
    {
        return $this->calcularCosteMaterialesProporcionales() + $this->calcularCosteMaterialesFijos();
    }

    public function calcularCosteLabor(): float
    {
        // El usuario indica que la mano de obra SIEMPRE es constante (fija)
        return $this->labores()
            ->get()
            ->reduce(function ($total, $labor) {
                return $total + ($labor->pivot->cantidad * $labor->precio);
            }, 0);
    }

    public function calcularCosteTotal(float $medidaZona = 1): float
    {
        $materiales = ($this->calcularCosteMaterialesProporcionales() * $medidaZona) + $this->calcularCosteMaterialesFijos();
        $labor = $this->calcularCosteLabor(); // Fijo independientemente de la medida

        return $materiales + $labor;
    }
}
