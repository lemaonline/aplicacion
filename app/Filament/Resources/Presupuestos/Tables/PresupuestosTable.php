<?php

namespace App\Filament\Resources\Presupuestos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PresupuestosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre_cliente')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('fecha_presupuesto')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('version')
                    ->label('Versión')
                    ->badge(),

                TextColumn::make('contacto_nombre')
                    ->label('Contacto')
                    ->searchable(),

                TextColumn::make('total_trasteros')
                    ->label('Trasteros')
                    ->getStateUsing(function ($record) {
                        return $record->zonas()->sum('num_trasteros') ?? 0;
                    }),

                TextColumn::make('total_m2')
                    ->label('M2 Total')
                    ->getStateUsing(function ($record) {
                        $total = $record->zonas()->sum('m2') ?? 0;
                        return number_format($total, 2);
                    }),

                TextColumn::make('total')
                    ->label('Total Presupuesto')
                    ->money('eur')
                    ->sortable(),

                SelectColumn::make('estado')
                    ->label('Estado')
                    ->options([
                        'activo' => 'Activo',
                        'inactivo' => 'Inactivo',
                        'contratado' => 'Contratado',
                        'instalado' => 'Instalado',
                    ])
                    ->sortable(),
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
