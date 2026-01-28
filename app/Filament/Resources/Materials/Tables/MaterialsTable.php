<?php

namespace App\Filament\Resources\Materials\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MaterialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function ($record) {
                        $html = $record->nombre;
                        if ($record->descripcion) {
                            $html .= '<div style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">' . $record->descripcion . '</div>';
                        }
                        return $html;
                    })
                    ->html(),

                TextColumn::make('unidad_medida')
                    ->label('Unidad de Medida')
                    ->sortable(),

                TextColumn::make('stock_actual')
                    ->label('Stock Actual')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('stock_minimo')
                    ->label('Stock Mínimo')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('precio')
                    ->label('Precio')
                    ->money('EUR')
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
