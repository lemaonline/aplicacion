<?php

namespace App\Filament\Widgets;

use App\Models\Presupuesto;
use App\Models\PiezaSnapshot;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class BudgetPiecesWidget extends BaseWidget
{
    public ?int $presupuestoId = null;

    protected static ?string $heading = 'Piezas Fabricadas (Detalle)';

    public static function canView(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                if (!$this->presupuestoId) {
                    return PiezaSnapshot::where('id', 0);
                }

                $presupuesto = Presupuesto::find($this->presupuestoId);
                $snapshot = $presupuesto?->snapshots()->latest()->first();

                if (!$snapshot) {
                    return PiezaSnapshot::where('id', 0);
                }

                return PiezaSnapshot::query()
                    ->join('zona_snapshots', 'pieza_snapshots.zona_snapshot_id', '=', 'zona_snapshots.id')
                    ->where('zona_snapshots.presupuesto_snapshot_id', $snapshot->id)
                    ->select('pieza_snapshots.*', 'zona_snapshots.nombre as zona_nombre');
            })
            ->columns([
                Tables\Columns\TextColumn::make('pieza_nombre')
                    ->label('Pieza')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('zona_nombre')
                    ->label('Zona')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('cantidad_total')
                    ->label('Cantidad')
                    ->suffix(' ud')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('coste_materiales')
                    ->label('Coste Mat.')
                    ->money('EUR')
                    ->alignRight(),
                Tables\Columns\TextColumn::make('coste_mano_obra')
                    ->label('Coste MO')
                    ->money('EUR')
                    ->alignRight(),
                Tables\Columns\TextColumn::make('coste_total')
                    ->label('Total Coste')
                    ->money('EUR')
                    ->weight('bold')
                    ->alignRight(),
            ])
            ->paginated(false);
    }
}
