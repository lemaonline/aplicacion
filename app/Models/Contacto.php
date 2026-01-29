<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contacto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'telefono',
        'email',
        'observaciones',
        'activo',
        'contactable_type',
        'contactable_id',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function contactable(): MorphTo
    {
        return $this->morphTo();
    }
}
