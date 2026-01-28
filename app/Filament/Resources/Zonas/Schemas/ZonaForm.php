<?php

namespace App\Filament\Resources\Zonas\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ZonaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(6)
            ->components([
                TextInput::make('altura_sistema')
                    ->label('Altura Sistema')
                    ->numeric()
                    ->required()
                    ->step(0.01)
                    ->columnSpan(2),

                Select::make('cerradura')
                    ->label('Cerradura')
                    ->options([
                        'normal' => 'Normal',
                        'automatica' => 'Automática',
                    ])
                    ->default('normal')
                    ->required()
                    ->columnSpan(2),

                Select::make('bisagra')
                    ->label('Bisagra')
                    ->options([
                        'normal' => 'Normal',
                        'muelle' => 'Con Muelle',
                    ])
                    ->default('normal')
                    ->required()
                    ->columnSpan(2),

                TextInput::make('m2')
                    ->label('M2')
                    ->numeric()
                    ->step(0.01)
                    ->columnSpan(1),

                TextInput::make('num_trasteros')
                    ->label('Nº Trasteros')
                    ->numeric()
                    ->columnSpan(1),

                TextInput::make('wp_500')
                    ->label('WP 500')
                    ->numeric()
                    ->step(0.01)
                    ->columnSpan(1),

                TextInput::make('wp_250')
                    ->label('WP 250')
                    ->numeric()
                    ->step(0.01)
                    ->columnSpan(1),

                TextInput::make('chapa_galva')
                    ->label('Chapa Galva')
                    ->numeric()
                    ->step(0.01)
                    ->columnSpan(1),

                TextInput::make('puerta_1000')
                    ->label('Puerta 1000')
                    ->numeric()
                    ->columnSpan(1),

                TextInput::make('puerta_750')
                    ->label('Puerta 750')
                    ->numeric()
                    ->columnSpan(1),

                TextInput::make('puerta_1500')
                    ->label('Puerta 1500')
                    ->numeric()
                    ->columnSpan(1),

                TextInput::make('puerta_2000')
                    ->label('Puerta 2000')
                    ->numeric()
                    ->columnSpan(1),

                TextInput::make('twin_750')
                    ->label('Twin 750')
                    ->numeric()
                    ->columnSpan(1),

                TextInput::make('twin_1000')
                    ->label('Twin 1000')
                    ->numeric()
                    ->columnSpan(1),

                Repeater::make('pasillos')
                    ->label('Pasillos')
                    ->schema([
                        TextInput::make('longitud')
                            ->label('Longitud')
                            ->numeric()
                            ->required()
                            ->step(0.01)
                            ->columnSpan(3),

                        TextInput::make('ancho')
                            ->label('Ancho')
                            ->numeric()
                            ->required()
                            ->step(0.01)
                            ->columnSpan(3),
                    ])
                    ->columns(6)
                    ->collapsed()
                    ->columnSpan(6),

                TextInput::make('malla_techo')
                    ->label('Malla Techo')
                    ->numeric()
                    ->step(0.01)
                    ->columnSpan(1),

                TextInput::make('tablero')
                    ->label('Tablero')
                    ->numeric()
                    ->step(0.01)
                    ->columnSpan(1),

                TextInput::make('esquinas')
                    ->label('Esquinas')
                    ->numeric()
                    ->step(0.01)
                    ->columnSpan(1),

                TextInput::make('extra_galva')
                    ->label('Extra Galva')
                    ->numeric()
                    ->step(0.01)
                    ->columnSpan(1),

                TextInput::make('extra_wp')
                    ->label('Extra WP')
                    ->numeric()
                    ->step(0.01)
                    ->columnSpan(1),

                TextInput::make('extra_damero')
                    ->label('Extra Damero')
                    ->numeric()
                    ->step(0.01)
                    ->columnSpan(1),
            ]);
    }
}
