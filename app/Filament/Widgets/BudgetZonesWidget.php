<?php

namespace App\Filament\Widgets;

use App\Models\Presupuesto;
use App\Models\ZonaSnapshot;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class BudgetZonesWidget extends BaseWidget
{
    public ?int $presupuestoId = null;

    protected static ?string $heading = 'Desglose por Zonas';

    public static function canView(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                if (!$this->presupuestoId) {
                    return ZonaSnapshot::where('id', 0); // Empty query
                }

                $presupuesto = Presupuesto::find($this->presupuestoId);
                $snapshot = $presupuesto?->snapshots()->latest()->first();

                if (!$snapshot) {
                    return ZonaSnapshot::where('id', 0);
                }

                return ZonaSnapshot::query()
                    ->where('presupuesto_snapshot_id', $snapshot->id)
                    ->select('zona_snapshots.*');
            })
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Zona')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('altura_sistema')
                    ->label('Altura')
                    ->suffix(' mm')
                    ->alignRight(),
                Tables\Columns\TextColumn::make('num_trasteros')
                    ->label('Trasteros')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('m2')
                    ->label('Superficie')
                    ->suffix(' m²')
                    ->alignRight(),
                Tables\Columns\TextColumn::make('total_coste_materiales')
                    ->label('Mat.')
                    ->money('EUR')
                    ->alignRight(),
                Tables\Columns\TextColumn::make('total_coste_fabricacion')
                    ->label('MO')
                    ->money('EUR')
                    ->alignRight(),
                Tables\Columns\TextColumn::make('total_coste_zona')
                    ->label('Total Coste')
                    ->money('EUR')
                    ->weight('bold')
                    ->alignRight(),
            ])
            ->paginated(false);
    }
}
