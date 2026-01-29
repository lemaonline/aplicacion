<?php

namespace App\Filament\Resources\Contactos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContactosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->searchable()
                    ->sortable()
                    ->description(fn($record) => $record->email),
                TextColumn::make('telefono')
                    ->searchable(),
                IconColumn::make('activo')
                    ->boolean(),
                TextColumn::make('contactable_type')
                    ->label('Tipo de Entidad')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'App\Models\Cliente' => 'Cliente',
                        'App\Models\Proveedor' => 'Proveedor',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'App\Models\Cliente' => 'info',
                        'App\Models\Proveedor' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('contactable.nombre')
                    ->label('Entidad Asignada')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
