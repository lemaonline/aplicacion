<?php

namespace App\Services;

use App\Models\Constante;
use App\Models\Labor;
use App\Models\MontajeSnapshot;
use App\Models\PiezaLaborSnapshot;
use App\Models\PiezaMaterialSnapshot;
use App\Models\PiezaSnapshot;
use App\Models\Presupuesto;
use App\Models\PresupuestoSnapshot;
use App\Models\ZonaSnapshot;
use Illuminate\Support\Facades\DB;

class PresupuestoSnapshotService
{
    /**
     * Crea un snapshot completo del presupuesto con todos sus datos
     */
    public function crear(Presupuesto $presupuesto): PresupuestoSnapshot
    {
        return DB::transaction(function () use ($presupuesto) {
            // 1. Eliminar snapshot anterior si existe
            $presupuesto->snapshots()->delete();

            // 2. Crear snapshot maestro
            $snapshot = PresupuestoSnapshot::create([
                'presupuesto_id' => $presupuesto->id,
                'margen_materiales' => $presupuesto->margen_materiales,
                'margen_mano_obra' => $presupuesto->margen_mano_obra,
                'margen_montaje' => $presupuesto->margen_montaje,
                'comision' => $presupuesto->comision,
            ]);

            // 3. Snapshot de zonas y sus cálculos
            $totalCosteMateriales = 0;
            $totalCosteFabricacion = 0;

            foreach ($presupuesto->zonas as $zona) {
                $zonaSnapshot = $this->snapshotZona($zona, $snapshot->id);
                $totalCosteMateriales += $zonaSnapshot->total_coste_materiales;
                $totalCosteFabricacion += $zonaSnapshot->total_coste_fabricacion;
            }

            // 4. Snapshot de montaje
            $totalCosteMontaje = 0;
            if ($presupuesto->montaje) {
                $montajeSnapshot = $this->snapshotMontaje($presupuesto->montaje, $snapshot->id);
                $totalCosteMontaje = $montajeSnapshot->coste_total_montaje;
            }

            // 5. Calcular totales con márgenes
            $margenMaterial = 1 + ($presupuesto->margen_materiales / 100);
            $margenLabor = 1 + ($presupuesto->margen_mano_obra / 100);
            $margenMontaje = 1 + ($presupuesto->margen_montaje / 100);

            $totalVentaMaterial = $totalCosteMateriales * $margenMaterial;
            $totalVentaLabor = $totalCosteFabricacion * $margenLabor;
            $totalVentaMontaje = $totalCosteMontaje * $margenMontaje;

            $totalVenta = $totalVentaMaterial + $totalVentaLabor + $totalVentaMontaje + $presupuesto->comision;

            // 6. Actualizar totales en snapshot
            $snapshot->update([
                'total_coste_materiales' => $totalCosteMateriales,
                'total_coste_fabricacion' => $totalCosteFabricacion,
                'total_coste_montaje' => $totalCosteMontaje,
                'total_venta' => $totalVenta,
            ]);

            // 7. Actualizar el total en el presupuesto
            $presupuesto->updateQuietly(['total' => $totalVenta]);

            return $snapshot;
        });
    }

    /**
     * Crea snapshot de una zona con todas sus piezas
     */
    protected function snapshotZona($zona, $snapshotId): ZonaSnapshot
    {
        // Crear snapshot de la zona
        $zonaSnapshot = ZonaSnapshot::create([
            'presupuesto_snapshot_id' => $snapshotId,
            'zona_id' => $zona->id,
            'nombre' => $zona->nombre,
            'altura_sistema' => $zona->altura_sistema,
            'cerradura' => $zona->cerradura,
            'bisagra' => $zona->bisagra,
            'm2' => $zona->m2,
            'num_trasteros' => $zona->num_trasteros,
            'wp_500' => $zona->wp_500,
            'wp_250' => $zona->wp_250,
            'chapa_galva' => $zona->chapa_galva,
            'puerta_1000' => $zona->puerta_1000,
            'puerta_750' => $zona->puerta_750,
            'puerta_1500' => $zona->puerta_1500,
            'puerta_2000' => $zona->puerta_2000,
            'twin_750' => $zona->twin_750,
            'twin_1000' => $zona->twin_1000,
            'malla_techo' => $zona->malla_techo,
            'tablero' => $zona->tablero,
            'esquinas' => $zona->esquinas,
            'extra_galva' => $zona->extra_galva,
            'extra_wp' => $zona->extra_wp,
            'extra_damero' => $zona->extra_damero,
            'pasillos' => $zona->pasillos,
        ]);

        // Snapshot de cada pieza calculada
        $totalMateriales = 0;
        $totalFabricacion = 0;

        foreach ($zona->calculos as $calculo) {
            $piezaSnapshot = $this->snapshotPieza($calculo, $zonaSnapshot->id);
            $totalMateriales += $piezaSnapshot->coste_materiales;
            $totalFabricacion += $piezaSnapshot->coste_mano_obra;
        }

        // Actualizar totales de la zona
        $zonaSnapshot->update([
            'total_coste_materiales' => $totalMateriales,
            'total_coste_fabricacion' => $totalFabricacion,
            'total_coste_zona' => $totalMateriales + $totalFabricacion,
        ]);

        return $zonaSnapshot;
    }

    /**
     * Crea snapshot de una pieza con todos sus materiales y labores
     */
    protected function snapshotPieza($calculo, $zonaSnapshotId): PiezaSnapshot
    {
        $pieza = $calculo->pieza;

        // Cargar relaciones de materiales y labores
        $pieza->load(['materiales', 'labores']);

        // Crear snapshot de la pieza
        $piezaSnapshot = PiezaSnapshot::create([
            'zona_snapshot_id' => $zonaSnapshotId,
            'pieza_id' => $pieza->id,
            'pieza_nombre' => $pieza->nombre,
            'pieza_referencia' => $pieza->referencia,
            'cantidad_total' => $calculo->cantidad_total,
            'factor_escala' => $calculo->factor_escala, // Nuevo campo
            'desglose_calculo' => $calculo->desglose_calculo,
            'precio_unitario' => $calculo->precio_unitario_snapshot,
        ]);

        $factorEscala = (float) $calculo->factor_escala;

        // Snapshot de materiales
        $costeMateriales = 0;
        if ($pieza->materiales) {
            foreach ($pieza->materiales as $material) {
                $cantidad = (float) $material->pivot->cantidad;
                $esProporcional = (bool) $material->pivot->es_proporcional;

                // Calcular cantidad real (cantidad * total_piezas * escala_si_es_proporcional)
                $cantidadReal = $cantidad * (float) $calculo->cantidad_total;

                if ($esProporcional) {
                    $cantidadReal *= $factorEscala;
                }

                $costeTotal = $cantidadReal * (float) $material->precio;

                PiezaMaterialSnapshot::create([
                    'pieza_snapshot_id' => $piezaSnapshot->id,
                    'material_id' => $material->id,
                    'material_nombre' => $material->nombre,
                    'material_referencia' => $material->referencia,
                    'material_unidad_medida' => $material->unidad_medida,
                    'cantidad' => $cantidadReal,
                    'es_proporcional' => $esProporcional,
                    'usa_factor_altura' => false,
                    'factor_altura_aplicado' => $esProporcional ? $factorEscala : null,
                    'precio_unitario' => $material->precio,
                    'coste_total' => $costeTotal,
                ]);

                $costeMateriales += $costeTotal;
            }
        }

        // Snapshot de labores
        $costeManoObra = 0;
        if ($pieza->labores) {
            foreach ($pieza->labores as $labor) {
                $cantidad = (float) $labor->pivot->cantidad;

                // La mano de obra por ahora es fija por pieza (minutos), no depende de la escala
                $cantidadReal = $cantidad * (float) $calculo->cantidad_total;

                $costeTotal = $cantidadReal * (float) $labor->precio;

                PiezaLaborSnapshot::create([
                    'pieza_snapshot_id' => $piezaSnapshot->id,
                    'labor_id' => $labor->id,
                    'labor_nombre' => $labor->nombre,
                    'labor_referencia' => $labor->referencia,
                    'labor_unidad_medida' => $labor->unidad_medida,
                    'cantidad' => $cantidadReal,
                    'usa_factor_altura' => false,
                    'factor_altura_aplicado' => null,
                    'precio_unitario' => $labor->precio,
                    'coste_total' => $costeTotal,
                ]);

                $costeManoObra += $costeTotal;
            }
        }

        // Actualizar costes de la pieza
        $piezaSnapshot->update([
            'coste_materiales' => $costeMateriales,
            'coste_mano_obra' => $costeManoObra,
            'coste_total' => $costeMateriales + $costeManoObra,
        ]);

        return $piezaSnapshot;
    }

    /**
     * Crea snapshot del montaje con todas las constantes
     */
    protected function snapshotMontaje($montaje, $snapshotId): MontajeSnapshot
    {
        // Obtener constantes actuales
        $precioPorTrastero = Constante::where('nombre', 'precio_por_trastero')->value('valor') ?? 0;
        $precioHoraInstalacion = Labor::where('nombre', 'Mano de obra de montaje')->value('precio') ?? 0;
        $dietaTrabajadorDia = Constante::where('nombre', 'dieta_trabajador_dia')->value('valor') ?? 0;
        $hospedajeTrabajadorDia = Constante::where('nombre', 'hospedaje_trabajador_dia')->value('valor') ?? 0;

        // Calcular costes según método
        $costeTransporte = ($montaje->numero_transportes ?? 0) * ($montaje->importe_unidad_transporte ?? 0);
        $costeTrasteros = 0;
        $costeManoObra = 0;
        $costeDietas = 0;
        $costeHospedaje = 0;
        $desglose = [];

        if ($montaje->metodo_calculo === 'autonomos') {
            $costeTrasteros = ($montaje->numero_trasteros ?? 0) * $precioPorTrastero;
            $desglose[] = "Método: Autónomos";
            $desglose[] = "{$montaje->numero_trasteros} trasteros × {$precioPorTrastero} EUR = {$costeTrasteros} EUR";
            $desglose[] = "{$montaje->numero_transportes} transportes × {$montaje->importe_unidad_transporte} EUR = {$costeTransporte} EUR";
        } else {
            // trabajadores_propios
            $dias = $montaje->dias_previstos_montaje ?? 0;
            $trabajadores = $montaje->numero_trabajadores ?? 0;
            $costeManoObra = $trabajadores * $dias * 8 * $precioHoraInstalacion;

            $desglose[] = "Método: Trabajadores Propios";
            $desglose[] = "{$trabajadores} trabajadores × {$dias} días × 8 horas × {$precioHoraInstalacion} EUR/h = {$costeManoObra} EUR";
            $desglose[] = "{$montaje->numero_transportes} transportes × {$montaje->importe_unidad_transporte} EUR = {$costeTransporte} EUR";

            if ($montaje->dietas) {
                $costeDietas = $dias * $trabajadores * $dietaTrabajadorDia;
                $desglose[] = "Dietas: {$trabajadores} trabajadores × {$dias} días × {$dietaTrabajadorDia} EUR = {$costeDietas} EUR";
            }

            if ($montaje->hospedaje && $dias > 0) {
                $diasHospedaje = max(0, $dias - 1);
                $costeHospedaje = $diasHospedaje * $trabajadores * $hospedajeTrabajadorDia;
                $desglose[] = "Hospedaje: {$trabajadores} trabajadores × {$diasHospedaje} noches × {$hospedajeTrabajadorDia} EUR = {$costeHospedaje} EUR";
            }
        }

        $costeTotalMontaje = $costeTrasteros + $costeManoObra + $costeTransporte + $costeDietas + $costeHospedaje;

        return MontajeSnapshot::create([
            'presupuesto_snapshot_id' => $snapshotId,
            'montaje_id' => $montaje->id,
            'numero_trasteros' => $montaje->numero_trasteros,
            'superficie_m2' => $montaje->superficie_m2,
            'numero_transportes' => $montaje->numero_transportes,
            'importe_unidad_transporte' => $montaje->importe_unidad_transporte,
            'numero_trabajadores' => $montaje->numero_trabajadores,
            'dias_previstos_montaje' => $montaje->dias_previstos_montaje,
            'dietas' => $montaje->dietas,
            'hospedaje' => $montaje->hospedaje,
            'metodo_calculo' => $montaje->metodo_calculo,
            'precio_por_trastero' => $precioPorTrastero,
            'precio_hora_instalacion' => $precioHoraInstalacion,
            'dieta_trabajador_dia' => $dietaTrabajadorDia,
            'hospedaje_trabajador_dia' => $hospedajeTrabajadorDia,
            'coste_trasteros' => $costeTrasteros,
            'coste_mano_obra' => $costeManoObra,
            'coste_transporte' => $costeTransporte,
            'coste_dietas' => $costeDietas,
            'coste_hospedaje' => $costeHospedaje,
            'coste_total_montaje' => $costeTotalMontaje,
            'desglose_calculo' => implode("\n", $desglose),
        ]);
    }

    /**
     * Obtiene el snapshot actual de un presupuesto
     */
    public function obtenerActual(Presupuesto $presupuesto): ?PresupuestoSnapshot
    {
        return $presupuesto->snapshots()->latest()->first();
    }

    /**
     * Regenera el snapshot (elimina el anterior y crea uno nuevo)
     */
    public function regenerar(Presupuesto $presupuesto): PresupuestoSnapshot
    {
        return $this->crear($presupuesto);
    }
}
