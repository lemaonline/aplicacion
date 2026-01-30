<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\AvanceObserver;

#[ObservedBy(AvanceObserver::class)]
class Avance extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'fecha' => 'date',
        'm2realizados' => 'decimal:2',
        'udrealizadas' => 'decimal:2',
    ];

    public function obra(): BelongsTo
    {
        return $this->belongsTo(Obra::class);
    }
}
