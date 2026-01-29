<?php

namespace App\Filament\Widgets;

use App\Models\Presupuesto;
use App\Models\PiezaMaterialSnapshot;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class BudgetMaterialsWidget extends BaseWidget
{
    public ?int $presupuestoId = null;

    public static function canView(): bool
    {
        return false;
    }

    protected static ?string $heading = 'Materiales Totales (Consolidado)';

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                if (!$this->presupuestoId) {
                    return PiezaMaterialSnapshot::where('id', 0);
                }

                $presupuesto = Presupuesto::find($this->presupuestoId);
                $snapshot = $presupuesto?->snapshots()->latest()->first();

                if (!$snapshot) {
                    return PiezaMaterialSnapshot::where('id', 0);
                }

                // Querying materials through zones and pieces
                return PiezaMaterialSnapshot::query()
                    ->join('pieza_snapshots', 'pieza_material_snapshots.pieza_snapshot_id', '=', 'pieza_snapshots.id')
                    ->join('zona_snapshots', 'pieza_snapshots.zona_snapshot_id', '=', 'zona_snapshots.id')
                    ->where('zona_snapshots.presupuesto_snapshot_id', $snapshot->id)
                    ->select(
                        'pieza_material_snapshots.material_id as id',
                        'pieza_material_snapshots.material_id',
                        'pieza_material_snapshots.material_nombre',
                        'pieza_material_snapshots.material_referencia',
                        'pieza_material_snapshots.material_unidad_medida',
                        DB::raw('SUM(pieza_material_snapshots.cantidad) as total_cantidad'),
                        'pieza_material_snapshots.precio_unitario',
                        DB::raw('SUM(pieza_material_snapshots.cantidad * pieza_material_snapshots.precio_unitario) as coste_total')
                    )
                    ->groupBy(
                        'pieza_material_snapshots.material_id',
                        'pieza_material_snapshots.material_nombre',
                        'pieza_material_snapshots.material_referencia',
                        'pieza_material_snapshots.material_unidad_medida',
                        'pieza_material_snapshots.precio_unitario'
                    );
            })
            ->columns([
                Tables\Columns\TextColumn::make('material_nombre')
                    ->label('Material')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('material_referencia')
                    ->label('Referencia')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('total_cantidad')
                    ->label('Cantidad')
                    ->formatStateUsing(fn($state, $record) => number_format($state, 2) . ' ' . $record->material_unidad_medida),
                Tables\Columns\TextColumn::make('precio_unitario')
                    ->label('Precio Unit.')
                    ->money('EUR'),
                Tables\Columns\TextColumn::make('coste_total')
                    ->label('Coste Total')
                    ->money('EUR')
                    ->weight('bold'),
            ])
            ->paginated(false);
    }
}
