<?php

namespace App\Filament\Resources\Piezas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PiezaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre de la pieza')
                    ->required()
                    ->maxLength(255),
                Textarea::make('descripcion')
                    ->label('Descripción')
                    ->nullable()
                    ->rows(3),
                Select::make('unidad_medida')
                    ->label('Unidad de medida')
                    ->options([
                        'ud' => 'Unidades (ud)',
                        'm' => 'Metros (m)',
                        'm2' => 'Metros cuadrados (m2)',
                    ])
                    ->required(),
                Select::make('tipo_elemento')
                    ->label('Tipo de elemento')
                    ->options([
                        'paramentos' => 'Paramentos',
                        'perfilerias' => 'Perfilerias',
                        'puertas' => 'Puertas',
                        'accesorios' => 'Accesorios',
                        'tornilleria' => 'Tornilleria',
                    ])
                    ->required(),
            ]);
    }
}
