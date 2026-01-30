<?php

namespace App\Observers;

use App\Models\Obra;

class ObraObserver
{
    /**
     * Handle the Obra "created" event.
     */
    public function created(Obra $obra): void
    {
        // Generar facturas automáticas basándose en los pagos 1-5
        for ($i = 1; $i <= 5; $i++) {
            $porcentaje = $obra->{"pago{$i}"};

            if ($porcentaje && $porcentaje > 0) {
                $importe = $obra->presupuesto * ($porcentaje / 100);

                \App\Models\Factura::create([
                    'fecha' => null,
                    'concepto' => "Pago {$i} ({$porcentaje}%) - {$obra->nombre}",
                    'importe' => $importe,
                    'numero_factura' => null,
                    'estado' => 'pendiente',
                    'cliente_id' => $obra->cliente_id,
                    'obra_id' => $obra->id,
                ]);
            }
        }
    }

    /**
     * Handle the Obra "updated" event.
     */
    public function updated(Obra $obra): void
    {
        //
    }

    /**
     * Handle the Obra "deleted" event.
     */
    public function deleted(Obra $obra): void
    {
        //
    }

    /**
     * Handle the Obra "restored" event.
     */
    public function restored(Obra $obra): void
    {
        //
    }

    /**
     * Handle the Obra "force deleted" event.
     */
    public function forceDeleted(Obra $obra): void
    {
        //
    }
}
