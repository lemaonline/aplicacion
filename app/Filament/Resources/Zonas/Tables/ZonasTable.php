<?php

namespace App\Filament\Resources\Zonas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ZonasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('altura_sistema')
                    ->label('Altura')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),

                TextColumn::make('cerradura')
                    ->label('Cerradura')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => ucfirst($state))
                    ->color(fn(string $state): string => $state === 'normal' ? 'gray' : 'blue'),

                TextColumn::make('bisagra')
                    ->label('Bisagra')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => $state === 'normal' ? 'Normal' : 'Con Muelle')
                    ->color(fn(string $state): string => $state === 'normal' ? 'gray' : 'blue'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
