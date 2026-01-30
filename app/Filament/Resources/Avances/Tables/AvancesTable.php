<?php

namespace App\Filament\Resources\Avances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AvancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('fecha')
                    ->date()
                    ->sortable(),
                TextColumn::make('obra.nombre')
                    ->label('Obra')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('realizado')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('totrealizado')
                    ->label('Total Realizado')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('m2realizados')
                    ->label('M² Realizados')
                    ->numeric()
                    ->suffix(' m²')
                    ->sortable(),
                TextColumn::make('udrealizadas')
                    ->label('Uds. Realizadas')
                    ->numeric()
                    ->sortable(),
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
