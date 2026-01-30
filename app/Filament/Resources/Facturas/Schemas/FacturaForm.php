<?php

namespace App\Filament\Resources\Facturas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FacturaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('cliente_id')
                    ->relationship('cliente', 'nombre')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('obra_id')
                    ->relationship('obra', 'nombre')
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('fecha'),
                TextInput::make('numero_factura')
                    ->default(null),
                TextInput::make('concepto')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('importe')
                    ->numeric()
                    ->suffix('€')
                    ->default(null),
                Select::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'realizada' => 'Realizada',
                        'pagada' => 'Pagada',
                    ])
                    ->default('pendiente')
                    ->required(),
            ]);
    }
}
