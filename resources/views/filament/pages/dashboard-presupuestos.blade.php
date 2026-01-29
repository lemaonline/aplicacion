<x-filament-panels::page>
    <div class="mb-6">
        {{ $this->form }}
    </div>

    @if ($presupuestoId)
        <div class="space-y-12 pb-12">
            {{-- Sección: Resumen Financiero --}}
            <section class="space-y-6">
                <h2 class="text-xl font-bold tracking-tight text-gray-900">Resumen del Proyecto</h2>
                <x-filament-widgets::widgets :columns="1" :data="$this->getWidgetData()" :widgets="[
                \App\Filament\Widgets\BudgetStatsWidget::class,
            ]" />
            </section>

            {{-- Sección: Detalle de Montaje y Logística --}}
            <section class="space-y-6">
                <div class="border-t border-gray-200 pt-8">
                    <h2 class="text-xl font-bold tracking-tight text-gray-900 mb-6">Montaje y Logística</h2>
                    <x-filament-widgets::widgets :columns="1" :data="$this->getWidgetData()" :widgets="[
                \App\Filament\Widgets\BudgetMontajeWidget::class,
            ]" />
                </div>
            </section>

            {{-- Sección: Distribución de Costes y Zonas --}}
            <section class="space-y-6">
                <div class="border-t border-gray-200 pt-8">
                    <h2 class="text-xl font-bold tracking-tight text-gray-900 mb-6">Distribución y Desglose por Zonas</h2>
                    <x-filament-widgets::widgets :columns="2" :data="$this->getWidgetData()" :widgets="[
                \App\Filament\Widgets\BudgetCostChartWidget::class,
                \App\Filament\Widgets\BudgetZonesWidget::class,
            ]" />
                </div>
            </section>

            {{-- Sección: Detalle Técnico (Piezas y Materiales) --}}
            <section class="space-y-10">
                <div class="border-t border-gray-200 pt-8">
                    <h2 class="text-xl font-bold tracking-tight text-gray-900 mb-6">Detalle Técnico de Fabricación</h2>
                    <x-filament-widgets::widgets :columns="1" :data="$this->getWidgetData()" :widgets="[
                \App\Filament\Widgets\BudgetPiecesWidget::class,
                \App\Filament\Widgets\BudgetMaterialsWidget::class,
            ]" />
                </div>
            </section>
        </div>
    @else
        <div class="flex items-center justify-center h-64 border-2 border-dashed border-gray-300 rounded-xl">
            <p class="text-gray-500 italic">Por favor, seleccione un presupuesto para comenzar el análisis.</p>
        </div>
    @endif
</x-filament-panels::page>