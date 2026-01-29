<?php

namespace App\Services;

use App\Models\Zona;
use App\Models\Receta;
use App\Models\ZonaCalculo;
use Illuminate\Support\Facades\DB;

class CalculoRecetaService
{
    /**
     * Realiza el cálculo automático de componentes para una zona recorriendo sus campos.
     */
    public function calcular(Zona $zona): void
    {
        DB::transaction(function () use ($zona) {
            // 1. Limpiar cálculos anteriores
            $zona->calculos()->delete();

            // 2. Definir los campos que pueden tener recetas
            $camposZonas = [
                'wp_500',
                'wp_250',
                'chapa_galva',
                'puerta_1000',
                'puerta_750',
                'puerta_1500',
                'puerta_2000',
                'twin_750',
                'twin_1000',
                'roller_750',
                'roller_1000',
                'roller_1500',
                'malla_techo',
                'tablero',
                'esquinas',
                'extra_galva',
                'extra_wp',
                'extra_damero',
                'num_trasteros',
                'pasillos'
            ];

            $resultados = [];

            // Ancho pasillo por defecto
            $anchoPasilloPorDefecto = 0;
            if (is_array($zona->pasillos) && count($zona->pasillos) > 0) {
                $anchoPasilloPorDefecto = (float) ($zona->pasillos[0]['ancho'] ?: 0) / 1000;
            }

            // 3. Buscar recetas para cada campo
            foreach ($camposZonas as $campo) {
                $valorOriginal = $zona->$campo;

                if ($campo === 'pasillos' && is_array($valorOriginal)) {
                    $this->procesarCampoComplejo($zona, 'pasillos', $valorOriginal, $resultados);
                } else {
                    $valorNumerico = (float) ($valorOriginal ?: 0);
                    if ($valorNumerico > 0) {
                        $this->procesarCampoSimple($zona, $campo, $valorNumerico, $anchoPasilloPorDefecto, $resultados);
                    }
                }
            }

            // 4. Guardar resultados consolidados
            foreach ($resultados as $piezaId => $data) {
                // Calculamos el coste desglosado para esta entrada de zona
                $costeMaterial = $data['cantidad_piezas'] * (($data['coste_material_prop'] * $data['escala']) + $data['coste_material_fijo']);
                $costeLabor = $data['cantidad_piezas'] * $data['coste_labor_fijo'];

                $precioUnitario = ($data['coste_prop_unitario'] * $data['escala']) + $data['coste_fijo_unitario'];
                $costeTotal = $costeMaterial + $costeLabor;

                $zona->calculos()->create([
                    'pieza_id' => $piezaId,
                    'cantidad_total' => $data['cantidad_piezas'],
                    'factor_escala' => $data['escala'],
                    'precio_unitario_snapshot' => $precioUnitario,
                    'coste_material' => $costeMaterial,
                    'coste_labor' => $costeLabor,
                    'coste_total' => $costeTotal,
                    'desglose_calculo' => implode("\n", $data['desglose']),
                ]);
            }

            // 5. Actualizar el total del presupuesto
            $this->actualizarTotalPresupuesto($zona->presupuesto);
        });
    }

    public function actualizarTotalPresupuesto(\App\Models\Presupuesto $presupuesto, bool $save = true): void
    {
        // 1. Obtener totales de costes base desde las zonas
        $costesZonas = DB::table('zona_calculos')
            ->join('zonas', 'zona_calculos.zona_id', '=', 'zonas.id')
            ->where('zonas.presupuesto_id', $presupuesto->id)
            ->selectRaw('SUM(coste_material) as total_material, SUM(coste_labor) as total_labor')
            ->first();

        $totalMaterial = (float) ($costesZonas->total_material ?: 0);
        $totalLabor = (float) ($costesZonas->total_labor ?: 0);

        // 2. Aplicar márgenes de beneficio
        $margenMaterial = 1 + (($presupuesto->margen_materiales ?: 0) / 100);
        $margenLabor = 1 + (($presupuesto->margen_mano_obra ?: 0) / 100);
        $margenMontaje = 1 + (($presupuesto->margen_montaje ?: 0) / 100);

        $totalVentaMaterial = $totalMaterial * $margenMaterial;
        $totalVentaLabor = $totalLabor * $margenLabor;

        // 3. Gestionar montaje
        $costeMontajeBase = $presupuesto->montaje ? $presupuesto->montaje->calcularCoste() : 0;
        $totalVentaMontaje = $costeMontajeBase * $margenMontaje;

        // 4. Sumar comisión
        $comision = (float) ($presupuesto->comision ?: 0);

        // 5. Total final
        $totalFinal = $totalVentaMaterial + $totalVentaLabor + $totalVentaMontaje + $comision;

        if ($save) {
            $presupuesto->update([
                'total' => $totalFinal,
            ]);
        } else {
            $presupuesto->total = $totalFinal;
        }
    }

    protected function procesarCampoSimple($zona, $campo, $valor, $anchoPasilloFactor, &$resultados)
    {
        $receta = Receta::where('campo_nombre', $campo)->with('items.pieza')->first();
        if (!$receta)
            return;

        foreach ($receta->items as $item) {
            // FILTRO DE CONDICIONES (Cerradura y Bisagra)
            if ($item->condicion_cerradura && $item->condicion_cerradura !== $zona->cerradura) {
                continue;
            }
            if ($item->condicion_bisagra && $item->condicion_bisagra !== $zona->bisagra) {
                continue;
            }

            $cantidadBase = (float) $item->cantidad_base;
            $cantidadPiezas = $valor * $cantidadBase;
            $formulaDesc = "{$valor} (en {$campo}) × {$cantidadBase}";

            $escala = 1.0;

            // SI HAY FÓRMULA, TIENE PRECEDENCIA
            if (!empty($item->formula)) {
                $valores = [
                    'VAL' => $valor,
                    'HS' => $zona->altura_sistema ?: 0,
                    'HP' => $zona->altura_puerta ?: 0,
                    'M2' => $zona->m2 ?: 0,
                    'TR' => $zona->num_trasteros ?: 0,
                    'AP' => $anchoPasilloFactor ?: 0,
                ];
                $cantidadPiezas = $this->resolverFormula($item->formula, $valores);
                $formulaDesc = "Fórmula: {$item->formula} [con VAL={$valor}, HS=" . ($valores['HS']) . ", HP=" . ($valores['HP']) . "]";
                $escala = 1.0; // En fórmulas la escala va implícita
            } else {
                // LÓGICA ANTIGUA DE ESCALA (FALLBACK)
                if ($item->referencia === 'altura' || $item->referencia === 'altura_sistema') {
                    $escala = ($zona->altura_sistema ?: 0) / 1000;
                    $formulaDesc .= " × {$escala}m (escala sistema)";
                } elseif ($item->referencia === 'altura_puerta') {
                    $escala = ($zona->altura_puerta ?: 0) / 1000;
                    $formulaDesc .= " × {$escala}m (escala puerta)";
                } elseif ($item->referencia === 'ancho_pasillo') {
                    $escala = $anchoPasilloFactor;
                    $formulaDesc .= " × {$escala}m (escala pasillo)";
                }
                $cantidadPiezas = $valor * $cantidadBase * $escala;
            }

            $this->agregarResultado($item, $cantidadPiezas, $escala, $formulaDesc, $resultados);
        }
    }

    protected function procesarCampoComplejo($zona, $campo, $items, &$resultados)
    {
        $receta = Receta::where('campo_nombre', $campo)->with('items.pieza')->first();
        if (!$receta)
            return;

        foreach ($items as $index => $dataItem) {
            $longitud = (float) ($dataItem['longitud'] ?: 0);
            $ancho = (float) ($dataItem['ancho'] ?: 0) / 1000;

            if ($longitud <= 0)
                continue;

            foreach ($receta->items as $item) {
                $cantidadBase = (float) $item->cantidad_base;
                $cantidadPiezas = $longitud * $cantidadBase;
                $formulaDesc = "{$longitud} (long. pasillo #" . ($index + 1) . ") × {$cantidadBase}";

                $escala = 1.0;

                if (!empty($item->formula)) {
                    $valores = [
                        'VAL' => $longitud,
                        'HS' => $zona->altura_sistema ?: 0,
                        'HP' => $zona->altura_puerta ?: 0,
                        'M2' => $zona->m2 ?: 0,
                        'TR' => $zona->num_trasteros ?: 0,
                        'AP' => $ancho ?: 0,
                    ];
                    $cantidadPiezas = $this->resolverFormula($item->formula, $valores);
                    $formulaDesc = "Fórmula Complex: {$item->formula} [VAL={$longitud}, HS={$valores['HS']}, HP={$valores['HP']}, AP={$ancho}]";
                    $escala = 1.0;
                } else {
                    if ($item->referencia === 'altura' || $item->referencia === 'altura_sistema') {
                        $escala = ($zona->altura_sistema ?: 0) / 1000;
                        $formulaDesc .= " × {$escala}m (escala sistema)";
                    } elseif ($item->referencia === 'altura_puerta') {
                        $escala = ($zona->altura_puerta ?: 0) / 1000;
                        $formulaDesc .= " × {$escala}m (escala puerta)";
                    } elseif ($item->referencia === 'ancho_pasillo') {
                        $escala = $ancho;
                        $formulaDesc .= " × {$escala}m (ancho propio)";
                    }
                    $cantidadPiezas = $longitud * $cantidadBase * $escala;
                }

                $this->agregarResultado($item, $cantidadPiezas, $escala, $formulaDesc, $resultados);
            }
        }
    }

    protected function agregarResultado($item, $cantidadPiezas, $escala, $formula, &$resultados)
    {
        $piezaId = $item->pieza_id;
        $pieza = $item->pieza;

        $costeMaterialProp = $pieza->calcularCosteMaterialesProporcionales();
        $costeMaterialFijo = $pieza->calcularCosteMaterialesFijos();
        $costeLaborFijo = $pieza->calcularCosteLabor();

        if (!isset($resultados[$piezaId])) {
            $resultados[$piezaId] = [
                'cantidad_piezas' => 0,
                'escala' => $escala,
                'coste_material_prop' => $costeMaterialProp,
                'coste_material_fijo' => $costeMaterialFijo,
                'coste_labor_fijo' => $costeLaborFijo,
                'coste_prop_unitario' => $costeMaterialProp,
                'coste_fijo_unitario' => $costeMaterialFijo + $costeLaborFijo,
                'desglose' => [],
            ];
        }

        $resultados[$piezaId]['cantidad_piezas'] += $cantidadPiezas;
        $resultados[$piezaId]['desglose'][] = $formula;
    }

    protected function resolverFormula($formula, $valores)
    {
        // 1. Reemplazar placeholders
        foreach ($valores as $key => $val) {
            $formula = str_replace("{{$key}}", (float) $val, $formula);
        }

        // 2. Limpieza estricta de seguridad
        $sanitized = preg_replace('/[^0-9\.\+\-\*\/\(\)\ ]/', '', $formula);

        if (empty($sanitized))
            return 0;

        try {
            // 3. Evaluar expresión matemática
            $resultado = eval ("return ($sanitized);");
            return is_numeric($resultado) ? (float) $resultado : 0;
        } catch (\Throwable $e) {
            return 0;
        }
    }
}
