<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GastoGeneral extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'importe',
        'periodicidad',
    ];

    public function getMonthlyImporte(): float
    {
        return match ($this->periodicidad) {
            'mensual' => (float) $this->importe,
            'trimestral' => (float) $this->importe / 3,
            'semestral' => (float) $this->importe / 6,
            'anual' => (float) $this->importe / 12,
            default => (float) $this->importe,
        };
    }

    public function getYearlyImporte(): float
    {
        return match ($this->periodicidad) {
            'mensual' => (float) $this->importe * 12,
            'trimestral' => (float) $this->importe * 4,
            'semestral' => (float) $this->importe * 2,
            'anual' => (float) $this->importe,
            default => (float) $this->importe * 12,
        };
    }
}
