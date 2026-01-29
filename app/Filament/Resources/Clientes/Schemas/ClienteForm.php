<?php

namespace App\Filament\Resources\Clientes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ClienteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required(),
                TextInput::make('cif')
                    ->default(null),
                TextInput::make('direccion')
                    ->default(null),
                TextInput::make('telefono')
                    ->tel()
                    ->default(null),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->default(null),
                Textarea::make('observaciones')
                    ->default(null)
                    ->columnSpanFull(),
                Toggle::make('activo')
                    ->required(),
            ]);
    }
}
