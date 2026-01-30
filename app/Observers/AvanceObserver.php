<?php

namespace App\Observers;

use App\Models\Avance;

class AvanceObserver
{
    public function creating(Avance $avance): void
    {
        $this->calculateRealizados($avance);
    }

    public function updating(Avance $avance): void
    {
        $this->calculateRealizados($avance);
    }

    protected function calculateRealizados(Avance $avance): void
    {
        if ($avance->obra_id && $avance->realizado !== null) {
            $obra = \App\Models\Obra::find($avance->obra_id);
            if ($obra) {
                $avance->m2realizados = ($obra->m2 * $avance->realizado) / 100;
                $avance->udrealizadas = ($obra->ud * $avance->realizado) / 100;
            }
        }
    }
}
