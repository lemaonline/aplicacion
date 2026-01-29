<?php

namespace App\Filament\Resources\Presupuestos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use App\Models\Presupuesto;
use Filament\Actions\Action;
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

                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => ucfirst($state))
                    ->color(fn(string $state): string => match ($state) {
                        'activo' => 'success',
                        'inactivo' => 'gray',
                        'contratado' => 'info',
                        'instalado' => 'primary',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('dashboard')
                    ->label('Análisis')
                    ->icon('heroicon-o-presentation-chart-bar')
                    ->color('info')
                    ->url(fn(Presupuesto $record): string => \App\Filament\Pages\DashboardPresupuestos::getUrl(['presupuesto_id' => $record->id])),
                EditAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
