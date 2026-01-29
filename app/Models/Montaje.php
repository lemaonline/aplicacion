<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Montaje extends Model
{
    protected $table = 'montajes';

    protected static function booted()
    {
        static::saved(function ($montaje) {
            if ($montaje->presupuesto) {
                app(\App\Services\CalculoRecetaService::class)->actualizarTotalPresupuesto($montaje->presupuesto);
                // El método anterior dispara el update() del presupuesto, 
                // lo cual a su vez dispara el snapshot en el modelo Presupuesto.
            }
        });
    }

    protected $fillable = [
        'presupuesto_id',
        'numero_trasteros',
        'superficie_m2',
        'numero_transportes',
        'importe_unidad_transporte',
        'numero_trabajadores',
        'dias_previstos_montaje',
        'dietas',
        'hospedaje',
        'metodo_calculo',
    ];

    public function presupuesto(): BelongsTo
    {
        return $this->belongsTo(Presupuesto::class);
    }

    /**
     * Calcula el coste de montaje usando el método seleccionado
     */
    public function calcularCoste(): float
    {
        return match ($this->metodo_calculo) {
            'autonomos' => $this->calcularCosteAutonomos(),
            'trabajadores_propios' => $this->calcularCosteTrabajadoresPropios(),
            default => $this->calcularCosteTrabajadoresPropios(),
        };
    }

    /**
     * Calcula el coste usando el método de autónomos:
     * (número_trasteros × precio_por_trastero) + coste_transporte
     */
    public function calcularCosteAutonomos(): float
    {
        $precioPorTrastero = Constante::where('nombre', 'precio_por_trastero')->value('valor') ?? 0;
        $costeTransporte = ($this->numero_transportes ?: 0) * ($this->importe_unidad_transporte ?: 0);
        $costeTrasteros = ($this->numero_trasteros ?: 0) * $precioPorTrastero;

        return (float) ($costeTrasteros + $costeTransporte);
    }

    /**
     * Calcula el coste usando el método de trabajadores propios:
     * (trabajadores × días × 8 horas × precio_hora_instalacion) + transporte + dietas + hospedaje
     * Nota: hospedaje es (días - 1) porque si trabajas N días, necesitas N-1 noches
     */
    public function calcularCosteTrabajadoresPropios(): float
    {
        $costeTransporte = ($this->numero_transportes ?: 0) * ($this->importe_unidad_transporte ?: 0);

        $dias = (int) ($this->dias_previstos_montaje ?: 0);
        $trabajadores = (int) ($this->numero_trabajadores ?: 0);
        $horasPorDia = 8;

        // Coste de mano de obra: trabajadores × días × 8 horas × precio_hora
        // Obtener precio desde el modelo Labor (Mano de obra de montaje)
        $precioHoraInstalacion = Labor::where('nombre', 'Mano de obra de montaje')->value('precio') ?? 0;
        $costeManoObra = $trabajadores * $dias * $horasPorDia * $precioHoraInstalacion;

        $costeDietas = 0;
        $costeHospedaje = 0;

        if ($this->dietas) {
            $precioDieta = Constante::where('nombre', 'dieta_trabajador_dia')->value('valor') ?? 0;
            $costeDietas = $dias * $trabajadores * $precioDieta;
        }

        if ($this->hospedaje && $dias > 0) {
            $precioHospedaje = Constante::where('nombre', 'hospedaje_trabajador_dia')->value('valor') ?? 0;
            // Hospedaje es (días - 1) porque si trabajas 4 días, solo necesitas 3 noches
            $diasHospedaje = max(0, $dias - 1);
            $costeHospedaje = $diasHospedaje * $trabajadores * $precioHospedaje;
        }

        return (float) ($costeManoObra + $costeTransporte + $costeDietas + $costeHospedaje);
    }
}
