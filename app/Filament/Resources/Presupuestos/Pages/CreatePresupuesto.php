<?php

namespace App\Filament\Resources\Presupuestos\Pages;

use App\Filament\Resources\Presupuestos\PresupuestoResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Log;

class CreatePresupuesto extends CreateRecord
{
    protected static string $resource = PresupuestoResource::class;

    protected function beforeCreate(): void
    {
        Log::info('CreatePresupuesto@beforeCreate', [
            'data_keys' => array_keys($this->data),
            'montaje' => $this->data['montaje'] ?? 'null',
        ]);

        // VALIDACIÓN DE TOTALES
        $zonas = $this->data['zonas'] ?? [];
        $totalTrasteros = 0;
        $totalM2 = 0;

        foreach ($zonas as $zona) {
            $totalTrasteros += (int) ($zona['num_trasteros'] ?? 0);
            $totalM2 += (float) ($zona['m2'] ?? 0);
        }

        $montajeData = $this->data['montaje'] ?? [];
        $montajeTrasteros = (int) ($montajeData['numero_trasteros'] ?? 0);
        $montajeM2 = (float) ($montajeData['superficie_m2'] ?? 0);

        Log::info('Calculando totales (Create)', [
            'zonas_trasteros' => $totalTrasteros,
            'zonas_m2' => $totalM2,
            'form_trasteros' => $montajeTrasteros,
            'form_m2' => $montajeM2,
        ]);

        if ($totalTrasteros !== $montajeTrasteros || abs($totalM2 - $montajeM2) > 0.01) {
            Notification::make()
                ->title('Totales desactualizados')
                ->body('Los totales de montaje no coinciden con la suma de las zonas. Por favor, pulsa el botón en la sección de Montaje.')
                ->warning()
                ->send();

            throw new Halt();
        }
    }

    protected function afterCreate(): void
    {
        if (!empty($this->data['montaje'])) {
            \App\Models\Montaje::updateOrCreate(
                ['presupuesto_id' => $this->record->id],
                $this->data['montaje']
            );
        }
    }
}
