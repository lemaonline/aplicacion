<?php

namespace App\Filament\Resources\Labors\Schemas;

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

                TextInput::make('precio_hora')
                    ->label('Precio por Hora')
                    ->numeric()
                    ->required(),
            ]);
    }
}
