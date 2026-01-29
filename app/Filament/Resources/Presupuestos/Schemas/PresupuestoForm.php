<?php

namespace App\Filament\Resources\Presupuestos\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PresupuestoForm
{
    public static function updateTotals($set, $get)
    {
        $zonas = $get('zonas') ?? [];
        $totalTrasteros = 0;
        $totalM2 = 0;

        foreach ($zonas as $zona) {
            $totalTrasteros += (int) ($zona['num_trasteros'] ?? 0);
            $totalM2 += (float) ($zona['m2'] ?? 0);
        }

        $set('montaje.numero_trasteros', $totalTrasteros);
        $set('montaje.superficie_m2', $totalM2);

    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                TextInput::make('nombre_cliente')
                    ->label('Cliente')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(7),


                DatePicker::make('fecha_presupuesto')
                    ->label('Fecha')
                    ->required()
                    ->default(now())
                    ->columnSpan(2),

                Select::make('version')
                    ->label('Versión')
                    ->options([
                        'v1' => 'v1',
                        'v2' => 'v2',
                        'v3' => 'v3',
                        'v4' => 'v4',
                        'v5' => 'v5',
                        'v6' => 'v6',
                        'v7' => 'v7',
                        'v8' => 'v8',
                        'v9' => 'v9',
                    ])
                    ->default('v1')
                    ->required()
                    ->columnSpan(1),

                Select::make('estado')
                    ->label('Estado')
                    ->options([
                        'activo' => 'Activo',
                        'inactivo' => 'Inactivo',
                        'contratado' => 'Contratado',
                        'instalado' => 'Instalado',
                    ])
                    ->default('activo')
                    ->required()
                    ->columnSpan(2),

                TextInput::make('contacto_telefono')
                    ->label('Teléfono')
                    ->nullable()
                    ->tel()
                    ->columnSpan(2),

                TextInput::make('contacto_nombre')
                    ->label('Contacto')
                    ->nullable()
                    ->maxLength(255)
                    ->columnSpan(5),

                TextInput::make('contacto_correo')
                    ->label('Correo')
                    ->nullable()
                    ->email()
                    ->columnSpan(5),

                Textarea::make('comentarios')
                    ->label('Comentarios')
                    ->nullable()
                    ->columnSpan(12),

                Section::make('Márgenes de Beneficio (%)')
                    ->schema([
                        TextInput::make('margen_materiales')
                            ->label('Materiales (%)')
                            ->numeric()
                            ->default(30)
                            ->minValue(0)
                            ->required()
                            ->columnSpan(3),

                        TextInput::make('margen_mano_obra')
                            ->label('Mano de Obra (%)')
                            ->numeric()
                            ->default(30)
                            ->minValue(0)
                            ->required()
                            ->columnSpan(3),

                        TextInput::make('margen_montaje')
                            ->label('Montaje (%)')
                            ->numeric()
                            ->default(30)
                            ->minValue(0)
                            ->required()
                            ->columnSpan(3),

                        TextInput::make('comision')
                            ->label('Comisión (€)')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->required()
                            ->prefix('€')
                            ->columnSpan(3),
                    ])
                    ->columns(12)
                    ->collapsible()
                    ->columnSpan(12),

                Section::make('Resumen de Costes (Automático)')
                    ->description('Cálculo consolidado basado en las recetas de todas las zonas.')
                    ->schema([
                        \Filament\Forms\Components\Placeholder::make('total_coste_base')
                            ->label('Total Coste Base')
                            ->content(function ($record) {
                                if (!$record)
                                    return '€0,00';

                                // Suma de todos los costes de piezas (Material + Labor)
                                $costePiezas = $record->zonas()->get()->flatMap->calculos->sum('coste_total') ?? 0;

                                // Coste de montaje base
                                $costeMontaje = $record->montaje ? $record->montaje->calcularCoste() : 0;

                                $total = $costePiezas + $costeMontaje;
                                return '€' . number_format($total, 2, ',', '.');
                            })
                            ->columnSpan(4),

                        \Filament\Forms\Components\Placeholder::make('precio_venta_estimado')
                            ->label('Precio Venta Sugerido (Precios Actuales)')
                            ->content(function ($record, $get) {
                                // 1. Costes Base de piezas (usando lo que hay en BD para estabilidad)
                                $totalMaterial = 0;
                                $totalLabor = 0;

                                if ($record) {
                                    $calculos = $record->zonas()->get()->flatMap->calculos;
                                    $totalMaterial = $calculos->sum('coste_material') ?? 0;
                                    $totalLabor = $calculos->sum('coste_labor') ?? 0;
                                }

                                // 2. Coste de montaje (calculado con datos actuales del formulario)
                                $costeMontajeBase = 0;
                                $metodo = $get('montaje.metodo_calculo');

                                if ($metodo === 'autonomos') {
                                    $precioPorTrastero = \App\Models\Constante::where('nombre', 'precio_por_trastero')->value('valor') ?? 0;
                                    $trasteros = (int) ($get('montaje.numero_trasteros') ?: 0);
                                    $transportes = (int) ($get('montaje.numero_transportes') ?: 0);
                                    $precioTrans = (float) ($get('montaje.importe_unidad_transporte') ?: 0);
                                    $costeMontajeBase = ($trasteros * $precioPorTrastero) + ($transportes * $precioTrans);
                                } elseif ($metodo === 'trabajadores_propios' || !$metodo) {
                                    $precioHora = \App\Models\Labor::where('nombre', 'Mano de obra de montaje')->value('precio') ?? 0;
                                    $trabajadores = (int) ($get('montaje.numero_trabajadores') ?: 0);
                                    $dias = (int) ($get('montaje.dias_previstos_montaje') ?: 0);
                                    $costeMontajeBase = $trabajadores * $dias * 8 * $precioHora;

                                    // Sumar otros costes si existen
                                    $transportes = (int) ($get('montaje.numero_transportes') ?: 0);
                                    $precioTrans = (float) ($get('montaje.importe_unidad_transporte') ?: 0);
                                    $costeMontajeBase += ($transportes * $precioTrans);
                                }

                                // 3. Márgenes
                                $margenMat = (float) ($get('margen_materiales') ?? ($record->margen_materiales ?: 0));
                                $margenLab = (float) ($get('margen_mano_obra') ?? ($record->margen_mano_obra ?: 0));
                                $margenMon = (float) ($get('margen_montaje') ?? ($record->margen_montaje ?: 0));
                                $comision = (float) ($get('comision') ?? ($record->comision ?: 0));

                                // 4. Totales
                                $ventaMaterial = $totalMaterial * (1 + ($margenMat / 100));
                                $ventaLabor = $totalLabor * (1 + ($margenLab / 100));
                                $ventaMontaje = $costeMontajeBase * (1 + ($margenMon / 100));

                                $totalSugerido = $ventaMaterial + $ventaLabor + $ventaMontaje + $comision;

                                return '€' . number_format($totalSugerido, 2, ',', '.');
                            })
                            ->helperText('Calculado con precios actuales de materiales y mano de obra')
                            ->columnSpan(4),

                        \Filament\Forms\Components\Placeholder::make('info_calculo')
                            ->label('Info')
                            ->content('Los costes se recalculan automáticamente al guardar cada zona.')
                            ->columnSpan(4),
                    ])
                    ->columns(12)
                    ->columnSpan(12),

                Repeater::make('zonas')
                    ->label('Zonas')
                    ->relationship()
                    ->addActionLabel('Añadir zona')
                    ->itemLabel(fn(array $state): ?string => $state['nombre'] ?? null)
                    ->schema([
                        // NOMBRE DE ZONA
                        TextInput::make('nombre')
                            ->label('Nombre Zona')
                            ->required()
                            ->columnSpan(3),

                        // PRIMERA LÍNEA: Altura, Cerradura, Bisagra, M2, Trasteros
                        TextInput::make('altura_sistema')
                            ->label('Altura (mm)')
                            ->numeric()
                            ->required()
                            ->step(0.01)
                            ->columnSpan(2),

                        Select::make('cerradura')
                            ->label('Cerradura')
                            ->options([
                                'normal' => 'Normal',
                                'automatica' => 'Automática',
                            ])
                            ->default('normal')
                            ->required()
                            ->columnSpan(2),

                        Select::make('bisagra')
                            ->label('Bisagra')
                            ->options([
                                'normal' => 'Normal',
                                'muelle' => 'Con Muelle',
                            ])
                            ->default('normal')
                            ->required()
                            ->columnSpan(2),

                        TextInput::make('m2')
                            ->label('M2')
                            ->numeric()
                            ->step(0.01)
                            ->columnSpan(2),

                        TextInput::make('num_trasteros')
                            ->label('Trasteros')
                            ->numeric()
                            ->columnSpan(1),

                        // SEGUNDA LÍNEA: Componentes principales
                        TextInput::make('wp_500')
                            ->label('WP 500')
                            ->numeric()
                            ->step(0.01)
                            ->columnSpan(1),

                        TextInput::make('wp_250')
                            ->label('WP 250')
                            ->numeric()
                            ->step(0.01)
                            ->columnSpan(1),

                        TextInput::make('chapa_galva')
                            ->label('Galva')
                            ->numeric()
                            ->step(0.01)
                            ->columnSpan(1),

                        TextInput::make('puerta_1000')
                            ->label('P1000')
                            ->numeric()
                            ->columnSpan(1),

                        TextInput::make('puerta_750')
                            ->label('P750')
                            ->numeric()
                            ->columnSpan(1),

                        TextInput::make('puerta_1500')
                            ->label('P1500')
                            ->numeric()
                            ->columnSpan(1),

                        TextInput::make('puerta_2000')
                            ->label('P2000')
                            ->numeric()
                            ->columnSpan(1),

                        TextInput::make('twin_750')
                            ->label('Twin 750')
                            ->numeric()
                            ->columnSpan(1),

                        TextInput::make('twin_1000')
                            ->label('Twin 1000')
                            ->numeric()
                            ->columnSpan(1),

                        TextInput::make('malla_techo')
                            ->label('Malla')
                            ->numeric()
                            ->step(0.01)
                            ->columnSpan(1),

                        TextInput::make('tablero')
                            ->label('Tablero')
                            ->numeric()
                            ->step(0.01)
                            ->columnSpan(1),

                        TextInput::make('esquinas')
                            ->label('Esquinas')
                            ->numeric()
                            ->step(0.01)
                            ->columnSpan(1),

                        // TERCERA LÍNEA: Extras
                        TextInput::make('extra_galva')
                            ->label('Extra Galva')
                            ->numeric()
                            ->step(0.01)
                            ->columnSpan(4),

                        TextInput::make('extra_wp')
                            ->label('Extra WP')
                            ->numeric()
                            ->step(0.01)
                            ->columnSpan(4),

                        TextInput::make('extra_damero')
                            ->label('Extra Damero')
                            ->numeric()
                            ->step(0.01)
                            ->columnSpan(4),

                        Repeater::make('pasillos')
                            ->label('Pasillos')
                            ->addActionLabel('Añadir pasillo')
                            ->schema([
                                TextInput::make('longitud')
                                    ->label('Longitud (m)')
                                    ->numeric()
                                    ->step(0.01),

                                TextInput::make('ancho')
                                    ->label('Ancho (mm)')
                                    ->numeric()
                                    ->step(0.01),
                            ])
                            ->columns(2)
                            ->columnSpan(12),
                    ])
                    ->columns(12)
                    ->collapsible()
                    ->columnSpan(12),

                Section::make('Montaje')
                    ->relationship('montaje')
                    ->headerActions([
                        Action::make('obtenerDatosZonas')
                            ->label('Obtener total m2 y unidades')
                            ->icon('heroicon-m-arrow-path')
                            ->color('info')
                            ->action(function ($set, $get) {
                                self::updateTotals($set, $get);
                                \Filament\Notifications\Notification::make()
                                    ->title('Datos sincronizados correctamente')
                                    ->success()
                                    ->send();
                            }),
                    ])
                    ->schema([
                        // === DATOS GENERALES ===
                        TextInput::make('numero_trasteros')
                            ->label('Número de Trasteros')
                            ->numeric()
                            ->minValue(0)
                            ->readOnly()
                            ->dehydrated(true)
                            ->live()
                            ->columnSpan(3),

                        TextInput::make('superficie_m2')
                            ->label('Superficie (m²)')
                            ->numeric()
                            ->minValue(0)
                            ->step('0.01')
                            ->readOnly()
                            ->dehydrated(true)
                            ->columnSpan(3),

                        TextInput::make('numero_transportes')
                            ->label('Número de Transportes')
                            ->numeric()
                            ->minValue(0)
                            ->live()
                            ->columnSpan(3),

                        TextInput::make('importe_unidad_transporte')
                            ->label('Importe/Transporte (€)')
                            ->numeric()
                            ->minValue(0)
                            ->step('0.01')
                            ->live()
                            ->columnSpan(3),

                        // Campos para trabajadores propios
                        TextInput::make('numero_trabajadores')
                            ->label('Número de Trabajadores')
                            ->numeric()
                            ->minValue(0)
                            ->live()
                            ->columnSpan(3),

                        TextInput::make('dias_previstos_montaje')
                            ->label('Días Previstos')
                            ->numeric()
                            ->minValue(0)
                            ->live()
                            ->columnSpan(3),

                        Checkbox::make('dietas')
                            ->label('Incluir Dietas')
                            ->live()
                            ->columnSpan(3),

                        Checkbox::make('hospedaje')
                            ->label('Incluir Hospedaje')
                            ->live()
                            ->columnSpan(3),

                        // === SELECTOR DE MÉTODO ===
                        \Filament\Forms\Components\ToggleButtons::make('metodo_calculo')
                            ->label('⚙️ Método de Cálculo a Aplicar al Presupuesto')
                            ->options([
                                'autonomos' => 'Autónomos',
                                'trabajadores_propios' => 'Trabajadores Propios',
                            ])
                            ->default('trabajadores_propios')
                            ->inline()
                            ->required()
                            ->columnSpan(12),

                        // === RESÚMENES DE COSTE ===
                        \Filament\Forms\Components\Placeholder::make('coste_autonomos_preview')
                            ->label('💼 Coste Montaje Autónomos')
                            ->content(function ($get) {
                                $numeroTrasteros = (int) ($get('numero_trasteros') ?: 0);
                                $numeroTransportes = (int) ($get('numero_transportes') ?: 0);
                                $importeTransporte = (float) ($get('importe_unidad_transporte') ?: 0);

                                $precioPorTrastero = \App\Models\Constante::where('nombre', 'precio_por_trastero')->value('valor') ?? 0;
                                $costeTransporte = $numeroTransportes * $importeTransporte;
                                $costeTrasteros = $numeroTrasteros * $precioPorTrastero;

                                $total = $costeTrasteros + $costeTransporte;
                                return '€' . number_format($total, 2, ',', '.') . ' (Trasteros: ' . $numeroTrasteros . ' × ' . number_format($precioPorTrastero, 2, ',', '.') . '€ + Transporte)';
                            })
                            ->columnSpan(6),


                        \Filament\Forms\Components\Placeholder::make('coste_trabajadores_preview')
                            ->label('👷 Coste Montaje Trabajadores Propios')
                            ->content(function ($get) {
                                $numeroTransportes = (int) ($get('numero_transportes') ?: 0);
                                $importeTransporte = (float) ($get('importe_unidad_transporte') ?: 0);
                                $dias = (int) ($get('dias_previstos_montaje') ?: 0);
                                $trabajadores = (int) ($get('numero_trabajadores') ?: 0);
                                $dietas = (bool) ($get('dietas') ?: false);
                                $hospedaje = (bool) ($get('hospedaje') ?: false);
                                $horasPorDia = 8;

                                // Coste de mano de obra: trabajadores × días × 8 horas × precio_hora
                                // Obtener precio desde el modelo Labor (Mano de obra de montaje)
                                $precioHoraInstalacion = \App\Models\Labor::where('nombre', 'Mano de obra de montaje')->value('precio') ?? 0;
                                $costeManoObra = $trabajadores * $dias * $horasPorDia * $precioHoraInstalacion;

                                $costeTransporte = $numeroTransportes * $importeTransporte;
                                $costeDietas = 0;
                                $costeHospedaje = 0;

                                if ($dietas) {
                                    $precioDieta = \App\Models\Constante::where('nombre', 'dieta_trabajador_dia')->value('valor') ?? 0;
                                    $costeDietas = $dias * $trabajadores * $precioDieta;
                                }

                                if ($hospedaje && $dias > 0) {
                                    $precioHospedaje = \App\Models\Constante::where('nombre', 'hospedaje_trabajador_dia')->value('valor') ?? 0;
                                    // Hospedaje es (días - 1) porque si trabajas 4 días, solo necesitas 3 noches
                                    $diasHospedaje = max(0, $dias - 1);
                                    $costeHospedaje = $diasHospedaje * $trabajadores * $precioHospedaje;
                                }

                                $total = $costeManoObra + $costeTransporte + $costeDietas + $costeHospedaje;

                                $detalles = [];
                                if ($costeManoObra > 0) {
                                    $detalles[] = 'M.Obra: ' . number_format($costeManoObra, 2, ',', '.') . '€';
                                }
                                if ($costeTransporte > 0) {
                                    $detalles[] = 'Transporte: ' . number_format($costeTransporte, 2, ',', '.') . '€';
                                }
                                if ($costeDietas > 0) {
                                    $detalles[] = 'Dietas: ' . number_format($costeDietas, 2, ',', '.') . '€';
                                }
                                if ($costeHospedaje > 0) {
                                    $detalles[] = 'Hospedaje: ' . number_format($costeHospedaje, 2, ',', '.') . '€';
                                }

                                $detalleTexto = !empty($detalles) ? ' (' . implode(' + ', $detalles) . ')' : '';

                                return '€' . number_format($total, 2, ',', '.') . $detalleTexto;
                            })
                            ->columnSpan(6),
                    ])
                    ->columns(12)
                    ->collapsible()
                    ->columnSpan(12),
            ]);
    }
}
