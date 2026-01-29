<?php

namespace App\Filament\Widgets;

use App\Models\Presupuesto;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BudgetMontajeWidget extends BaseWidget
{
    public ?int $presupuestoId = null;

    public static function canView(): bool
    {
        return false;
    }

    protected function getStats(): array
    {
        if (!$this->presupuestoId) {
            return [];
        }

        $presupuesto = Presupuesto::find($this->presupuestoId);
        $snapshot = $presupuesto?->snapshots()->latest()->first();

        if (!$snapshot) {
            return [];
        }

        $montaje = $snapshot->montaje;

        if (!$montaje) {
            return [
                Stat::make('Montaje', 'No definido')
                    ->description('No se han guardado datos de montaje')
                    ->color('gray'),
            ];
        }

        $metodo = $montaje->metodo_calculo === 'autonomos' ? 'Autónomos' : 'Personal Propio';

        $stats = [
            Stat::make('Método de Montaje', $metodo)
                ->description('Sistema de cálculo seleccionado')
                ->icon('heroicon-m-wrench-screwdriver')
                ->color('info'),
        ];

        // Transporte
        $transporteText = "{$montaje->numero_transportes} viaje(s) × " . number_format($montaje->importe_unidad_transporte, 2, ',', '.') . "€";
        $stats[] = Stat::make('Logística / Transporte', "€" . number_format($montaje->coste_transporte, 2, ',', '.'))
            ->description($transporteText)
            ->icon('heroicon-m-truck')
            ->color('success');

        if ($montaje->metodo_calculo === 'trabajadores_propios') {
            $personalText = "{$montaje->numero_trabajadores} op. × {$montaje->dias_previstos_montaje} días";
            $stats[] = Stat::make('Personal e Instalación', "€" . number_format($montaje->coste_mano_obra, 2, ',', '.'))
                ->description($personalText)
                ->icon('heroicon-m-users')
                ->color('warning');

            $dietasHospedaje = [];
            if ($montaje->dietas)
                $dietasHospedaje[] = 'Dietas';
            if ($montaje->hospedaje)
                $dietasHospedaje[] = 'Hospedaje';

            $extrasTotal = $montaje->coste_dietas + $montaje->coste_hospedaje;
            $stats[] = Stat::make('Dietas y Hospedaje', "€" . number_format($extrasTotal, 2, ',', '.'))
                ->description(count($dietasHospedaje) > 0 ? implode(' + ', $dietasHospedaje) : 'Sin extras')
                ->icon('heroicon-m-home-modern')
                ->color($extrasTotal > 0 ? 'warning' : 'gray');
        } else {
            $trasterosText = "{$montaje->numero_trasteros} trasteros × " . number_format($montaje->precio_por_trastero, 2, ',', '.') . "€";
            $stats[] = Stat::make('Coste por Unidades', "€" . number_format($montaje->coste_trasteros, 2, ',', '.'))
                ->description($trasterosText)
                ->icon('heroicon-m-squares-plus')
                ->color('warning');
        }

        return $stats;
    }
}
