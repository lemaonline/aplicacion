<?php

namespace App\Livewire;

use App\Models\GastoGeneral;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GastoGeneralStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $records = GastoGeneral::all();

        $totalMes = $records->sum(fn($record) => $record->getMonthlyImporte());
        $totalAño = $records->sum(fn($record) => $record->getYearlyImporte());

        return [
            Stat::make('Total Mes', number_format($totalMes, 2, ',', '.') . ' €')
                ->description('Prorrateo mensual')
                ->color('success'),
            Stat::make('Total Año', number_format($totalAño, 2, ',', '.') . ' €')
                ->description('Proyección anual')
                ->color('info'),
        ];
    }
}
