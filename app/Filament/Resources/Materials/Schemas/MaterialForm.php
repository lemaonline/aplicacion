<?php

namespace App\Filament\Resources\Materials\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class MaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),

                Textarea::make('descripcion')
                    ->label('Descripción')
                    ->nullable(),

                Select::make('unidad_medida')
                    ->label('Unidad de Medida')
                    ->options([
                        'ud' => 'Unidad (ud)',
                        'kg' => 'Kilogramos (kg)',
                        'm' => 'Metros (m)',
                        'm2' => 'Metros Cuadrados (m²)',
                    ])
                    ->required(),

                TextInput::make('stock_actual')
                    ->label('Stock Actual')
                    ->numeric()
                    ->default(0)
                    ->required(),

                TextInput::make('stock_minimo')
                    ->label('Stock Mínimo')
                    ->numeric()
                    ->default(0)
                    ->required(),

                TextInput::make('precio')
                    ->label('Precio')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }
}
