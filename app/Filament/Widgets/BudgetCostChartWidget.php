<?php

namespace App\Filament\Widgets;

use App\Models\Presupuesto;
use Filament\Widgets\ChartWidget;

class BudgetCostChartWidget extends ChartWidget
{
    public ?int $presupuestoId = null;

    public static function canView(): bool
    {
        return false;
    }

    protected ?string $heading = 'Desglose de Costes';

    protected function getData(): array
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
                'datasets' => [],
                'labels' => [],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Costes',
                    'data' => [
                        (float) $snapshot->total_coste_materiales,
                        (float) $snapshot->total_coste_fabricacion,
                        (float) $snapshot->total_coste_montaje,
                        (float) $snapshot->comision,
                    ],
                    'backgroundColor' => [
                        '#fbbf24', // Amber (Materials)
                        '#8b5cf6', // Violet (Labor)
                        '#10b981', // Emerald (Montage)
                        '#6b7280', // Gray (Commission)
                    ],
                ],
            ],
            'labels' => ['Materiales', 'Mano de Obra', 'Montaje', 'Comisión'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
