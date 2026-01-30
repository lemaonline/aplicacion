<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Presupuesto extends Model
{
    protected $fillable = [
        'nombre_cliente',
        'referencia',
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

            // Calcular referencia combinando nombre y versión
            $model->referencia = $model->nombre_cliente . ' - ' . ($model->version ?? 'v1');

            // Si cambian márgenes o comisión, forzamos recálculo si ya tiene zonas
            // Nota: El total se recalcula sumando los costes de las piezas + márgenes + comisión
            if ($model->isDirty(['margen_materiales', 'margen_mano_obra', 'margen_montaje', 'comision'])) {
                // No llamamos a calcular() de zona porque eso borra y recrea piezas
                // Solo llamamos a actualizarTotalPresupuesto para reaplicar márgenes sobre lo ya calculado
                // Pasamos false para que no haga un update() interno y evitemos bucles
                app(\App\Services\CalculoRecetaService::class)->actualizarTotalPresupuesto($model, false);
            }
        });

        // Generar snapshot después de guardar (cuando ya existen zonas y montaje)
        static::saved(function ($model) {
            // Solo generar snapshot si tiene zonas (presupuesto completo)
            if ($model->zonas()->exists()) {
                app(\App\Services\PresupuestoSnapshotService::class)->crear($model);
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

    public function snapshots(): HasMany
    {
        return $this->hasMany(PresupuestoSnapshot::class);
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
