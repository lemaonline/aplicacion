<?php

namespace App\Filament\Resources\Contactos\Schemas;

use App\Models\Cliente;
use App\Models\Proveedor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class ContactoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),

                TextInput::make('telefono')
                    ->tel()
                    ->maxLength(255)
                    ->default(null),

                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->maxLength(255)
                    ->default(null),

                Select::make('contactable_type')
                    ->label('Tipo de Contacto')
                    ->options([
                        Cliente::class => 'Cliente',
                        Proveedor::class => 'Proveedor',
                    ])
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn($set) => $set('contactable_id', null)),

                Select::make('contactable_id')
                    ->label('Entidad Asignada')
                    ->required()
                    ->options(function ($get) {
                        $type = $get('contactable_type');

                        if (!$type) {
                            return [];
                        }

                        if ($type === Cliente::class) {
                            return Cliente::all()->pluck('nombre', 'id');
                        }

                        if ($type === Proveedor::class) {
                            return Proveedor::all()->pluck('nombre', 'id');
                        }

                        return [];
                    })
                    ->searchable()
                    ->hidden(fn($get) => !$get('contactable_type')),

                Textarea::make('observaciones')
                    ->default(null)
                    ->columnSpanFull(),

                Toggle::make('activo')
                    ->required()
                    ->default(true),
            ]);
    }
}
