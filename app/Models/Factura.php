<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Factura extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'fecha' => 'date',
        'importe' => 'double',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function obra(): BelongsTo
    {
        return $this->belongsTo(Obra::class);
    }
}
