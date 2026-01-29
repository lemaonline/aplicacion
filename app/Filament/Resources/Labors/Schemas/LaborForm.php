<?php

namespace App\Filament\Resources\Labors\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LaborForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),

                Select::make('unidad_medida')
                    ->label('Unidad de Medida')
                    ->options([
                        'hora' => 'Hora',
                        'minuto' => 'Minuto',
                    ])
                    ->default('hora')
                    ->required(),

                TextInput::make('precio')
                    ->label('Precio')
                    ->numeric()
                    ->required(),
            ]);
    }
}
