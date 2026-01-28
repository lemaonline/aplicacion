<?php

namespace App\Filament\Resources\Piezas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PiezasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('tipo_elemento')
                    ->label('Tipo')
                    ->badge()
                    ->color('warning'),
                TextColumn::make('unidad_medida')
                    ->label('Unidad')
                    ->badge()
                    ->color('info'),
                TextColumn::make('coste_materiales')
                    ->label('Coste Materiales')
                    ->getStateUsing(fn ($record) => '€' . number_format($record->calcularCosteMateriales(), 2, ',', '.'))
                    ->badge()
                    ->color('success'),
                TextColumn::make('coste_labor')
                    ->label('Coste Mano de Obra')
                    ->getStateUsing(fn ($record) => '€' . number_format($record->calcularCosteLabor(), 2, ',', '.'))
                    ->badge()
                    ->color('danger'),
                TextColumn::make('coste_total')
                    ->label('Coste Total')
                    ->getStateUsing(fn ($record) => '€' . number_format($record->calcularCosteTotal(), 2, ',', '.'))
                    ->badge()
                    ->color('info'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                CreateAction::make(),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
