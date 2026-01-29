<?php

namespace App\Filament\Widgets;

use App\Models\Presupuesto;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BudgetStatsWidget extends BaseWidget
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
        if (!$presupuesto)
            return [];

        $snapshot = $presupuesto->snapshots()->latest()->first();

        if (!$snapshot) {
            return [
                Stat::make('Estado', 'Sin Snapshot')
                    ->description('Guarde el presupuesto para generar datos de análisis')
                    ->color('warning'),
            ];
        }

        $beneficioBruto = $snapshot->total_venta - ($snapshot->total_coste_materiales + $snapshot->total_coste_fabricacion + $snapshot->total_coste_montaje + $snapshot->comision);
        $margenReal = $snapshot->total_venta > 0 ? ($beneficioBruto / $snapshot->total_venta) * 100 : 0;

        return [
            Stat::make('Total Venta', '€' . number_format($snapshot->total_venta, 2, ',', '.'))
                ->icon('heroicon-o-currency-euro')
                ->color('success'),
            Stat::make('Margen Real', number_format($margenReal, 1) . '%')
                ->description('Beneficio sobre venta')
                ->icon('heroicon-o-chart-pie')
                ->color($margenReal > 20 ? 'success' : 'warning'),
            Stat::make('Coste Base', '€' . number_format($snapshot->total_coste_materiales + $snapshot->total_coste_fabricacion + $snapshot->total_coste_montaje, 2, ',', '.'))
                ->icon('heroicon-o-cog-6-tooth')
                ->color('info'),
        ];
    }
}
