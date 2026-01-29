<?php

namespace App\Filament\Resources\Presupuestos\Pages;

use App\Filament\Resources\Presupuestos\PresupuestoResource;
use App\Models\Presupuesto;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Log;

class EditPresupuesto extends EditRecord
{
    protected static string $resource = PresupuestoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        Log::info('EditPresupuesto@beforeSave', [
            'data_keys' => array_keys($this->data),
            'montaje' => $this->data['montaje'] ?? 'null',
        ]);

        // 1. VALIDACIÓN DE TOTALES
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

        Log::info('Calculando totales', [
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

        // 2. LÓGICA DE VERSIONADO
        $versionOriginal = $this->record->version;
        $versionNueva = $this->data['version'] ?? null;

        if ($versionOriginal !== $versionNueva && $versionNueva !== null) {
            $existente = Presupuesto::where('nombre_cliente', $this->record->nombre_cliente)
                ->where('version', $versionNueva)
                ->where('id', '!=', $this->record->id)
                ->first();

            if ($existente) {
                Notification::make()
                    ->title('Presupuesto existente')
                    ->body("Ya existe un presupuesto con el nombre \"{$this->record->nombre_cliente}\" versión {$versionNueva}")
                    ->warning()
                    ->send();

                $this->data['version'] = $versionOriginal;
                throw new Halt();
            }

            // CREAR LA NUEVA VERSIÓN
            // Replicamos el presupuesto pero le aplicamos los datos actuales del formulario
            $nuevoPresupuesto = $this->record->replicate();
            // Llenamos con los datos del formulario (excepto relaciones que van aparte)
            $formData = collect($this->data)->except(['zonas', 'montaje'])->toArray();
            $nuevoPresupuesto->fill($formData);
            $nuevoPresupuesto->version = $versionNueva;
            $nuevoPresupuesto->save();

            // REPLICAR ZONAS CON DATOS DEL FORMULARIO
            foreach ($zonas as $zonaData) {
                // Si la zona existe (tiene ID), la replicamos y actualizamos. Si no, creamos nueva.
                // Pero como es una nueva versión de presupuesto completa, simplemente creamos todas nuevas vinculadas al nuevo ID.
                $nuevaZona = new \App\Models\Zona();
                $nuevaZona->fill($zonaData);
                $nuevaZona->presupuesto_id = $nuevoPresupuesto->id;
                $nuevaZona->save();
            }

            // REPLICAR MONTAJE CON DATOS DEL FORMULARIO
            if (!empty($montajeData)) {
                $nuevoMontaje = new \App\Models\Montaje();
                $nuevoMontaje->fill($montajeData);
                $nuevoMontaje->presupuesto_id = $nuevoPresupuesto->id;
                $nuevoMontaje->save();
            }

            Notification::make()
                ->title('Copia creada')
                ->body("Nueva versión {$versionNueva} creada correctamente.")
                ->success()
                ->send();

            // Detenemos el guardado del registro actual para no modificar el original
            // Y redirigimos al nuevo
            $this->redirect(static::getResource()::getUrl('edit', ['record' => $nuevoPresupuesto]));
            throw new Halt();
        }
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    protected function afterSave(): void
    {
        // Forzamos el guardado de los datos de montaje si existen en el form
        if (!empty($this->data['montaje'])) {
            \App\Models\Montaje::updateOrCreate(
                ['presupuesto_id' => $this->record->id],
                $this->data['montaje']
            );
        }
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('edit', ['record' => $this->record]);
    }
}
