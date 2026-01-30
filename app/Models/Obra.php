<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\ObraObserver;

#[ObservedBy(ObraObserver::class)]
class Obra extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'presupuesto' => 'double',
        'm2' => 'double',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function facturas(): HasMany
    {
        return $this->hasMany(Factura::class);
    }

    public function avances(): HasMany
    {
        return $this->hasMany(Avance::class);
    }

    // Relaciones pendientes (comentadas hasta que existan los modelos)
    /*
    public function diarios(): HasMany
    {
        return $this->hasMany(Diario::class);
    }
    */
}
