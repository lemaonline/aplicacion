<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Presupuesto extends Model
{
    protected $fillable = [
        'nombre_cliente',
        'comentarios',
        'fecha_presupuesto',
        'version',
        'contacto_nombre',
        'contacto_telefono',
        'contacto_correo',
        'total',
        'estado',
        'margen_materiales',
        'margen_mano_obra',
        'margen_montaje',
        'comision',
    ];

    protected $casts = [
        'fecha_presupuesto' => 'date',
        'margen_materiales' => 'float',
        'margen_mano_obra' => 'float',
        'margen_montaje' => 'float',
        'comision' => 'float',
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            if (empty($model->estado)) {
                $model->estado = 'activo';
            }
        });
    }

    public function zonas(): HasMany
    {
        return $this->hasMany(Zona::class);
    }

    public function montaje(): HasOne
    {
        return $this->hasOne(Montaje::class);
    }

    public function getTotalTrasteros(): int
    {
        return $this->zonas()->sum('num_trasteros') ?? 0;
    }

    public function getTotalM2(): float
    {
        return $this->zonas()->sum('m2') ?? 0;
    }
}
