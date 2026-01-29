<?php

namespace App\Filament\Pages;

use App\Models\Presupuesto;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use UnitEnum;

class DashboardPresupuestos extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static string|UnitEnum|null $navigationGroup = 'Dashboard';

    protected static ?string $navigationLabel = 'Análisis de Presupuestos';

    protected static ?string $title = 'Análisis de Presupuestos';

    protected string $view = 'filament.pages.dashboard-presupuestos';

    public ?array $data = [];

    public ?int $presupuestoId = null;

    public function mount(): void
    {
        $this->presupuestoId = request()->query('presupuesto_id');

        $this->form->fill([
            'presupuesto_id' => $this->presupuestoId,
        ]);
    }

    public function form(\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
    {
        return $form
            ->schema([
                Select::make('presupuesto_id')
                    ->label('Seleccionar Presupuesto')
                    ->options(Presupuesto::query()->pluck('nombre_cliente', 'id'))
                    ->required()
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(fn($state) => $this->presupuestoId = $state),
            ])
            ->statePath('data');
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }

    protected function getFooterWidgets(): array
    {
        return [];
    }

    public function getWidgetData(): array
    {
        return [
            'presupuesto_id' => $this->presupuestoId,
            'presupuestoId' => $this->presupuestoId,
        ];
    }

    public function getFooterWidgetsColumns(): int|array
    {
        return 1;
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 1;
    }
}
