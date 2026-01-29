<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proveedor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'cif',
        'direccion',
        'telefono',
        'email',
        'observaciones',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function contactos(): MorphMany
    {
        return $this->morphMany(Contacto::class, 'contactable');
    }
}
