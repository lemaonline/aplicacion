<?php

namespace App\Filament\Resources\Obras\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ObraForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(4)
                    ->schema([
                        Select::make('nombre')
                            ->label('Obra (Desde Presupuesto)')
                            ->options(\App\Models\Presupuesto::all()->pluck('referencia', 'referencia'))
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                if (!$state)
                                    return;
                                $presupuesto = \App\Models\Presupuesto::where('referencia', $state)->first();
                                if ($presupuesto) {
                                    $set('m2', $presupuesto->getTotalM2());
                                    $set('ud', $presupuesto->getTotalTrasteros());
                                    $set('presupuesto', $presupuesto->total);

                                    // Intentar vincular cliente por nombre si existe
                                    if ($presupuesto->nombre_cliente) {
                                        $cliente = \App\Models\Cliente::where('nombre', 'like', '%' . $presupuesto->nombre_cliente . '%')->first();
                                        if ($cliente) {
                                            $set('cliente_id', $cliente->id);
                                        }
                                    }
                                }
                            }),
                        TextInput::make('m2')
                            ->label('M²')
                            ->numeric()
                            ->default(null),
                        TextInput::make('ud')
                            ->label('Unidades')
                            ->numeric()
                            ->default(null),
                        TextInput::make('presupuesto')
                            ->label('Importe Total')
                            ->numeric()
                            ->default(null)
                            ->prefix('€'),
                    ]),

                Grid::make(2)
                    ->schema([
                        TextInput::make('direccion')
                            ->label('Dirección de Obra')
                            ->default(null),
                        Select::make('cliente_id')
                            ->relationship('cliente', 'nombre')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('nombre')
                                    ->required(),
                                TextInput::make('email')
                                    ->email(),
                            ])
                            ->required(),
                    ]),

                Section::make('Plan de Pagos')
                    ->schema([
                        Grid::make(5)
                            ->schema([
                                TextInput::make('pago1')
                                    ->label('Pago 1 (%)')
                                    ->numeric()
                                    ->default(null)
                                    ->suffix('%'),
                                TextInput::make('pago2')
                                    ->label('Pago 2 (%)')
                                    ->numeric()
                                    ->default(null)
                                    ->suffix('%'),
                                TextInput::make('pago3')
                                    ->label('Pago 3 (%)')
                                    ->numeric()
                                    ->default(null)
                                    ->suffix('%'),
                                TextInput::make('pago4')
                                    ->label('Pago 4 (%)')
                                    ->numeric()
                                    ->default(null)
                                    ->suffix('%'),
                                TextInput::make('pago5')
                                    ->label('Pago 5 (%)')
                                    ->numeric()
                                    ->default(null)
                                    ->suffix('%'),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }
}
