<?php

namespace App\Filament\Resources\Presupuestos\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
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
                        TextInput::make('numero_trasteros')
                            ->label('Número de Trasteros')
                            ->numeric()
                            ->minValue(0)
                            ->readOnly()
                            ->dehydrated(true)
                            ->columnSpan(2),

                        TextInput::make('superficie_m2')
                            ->label('Superficie (m²)')
                            ->numeric()
                            ->minValue(0)
                            ->step('0.01')
                            ->readOnly()
                            ->dehydrated(true)
                            ->columnSpan(2),

                        TextInput::make('numero_transportes')
                            ->label('Número de Transportes')
                            ->numeric()
                            ->minValue(0)
                            ->columnSpan(2),

                        TextInput::make('importe_unidad_transporte')
                            ->label('Importe/Transporte (€)')
                            ->numeric()
                            ->minValue(0)
                            ->step('0.01')
                            ->columnSpan(2),

                        TextInput::make('numero_trabajadores')
                            ->label('Número de Trabajadores')
                            ->numeric()
                            ->minValue(0)
                            ->columnSpan(2),

                        TextInput::make('dias_previstos_montaje')
                            ->label('Días Previstos de Montaje')
                            ->numeric()
                            ->minValue(0)
                            ->columnSpan(2),

                        Checkbox::make('dietas')
                            ->label('Dietas')
                            ->columnSpan(1),

                        Checkbox::make('hospedaje')
                            ->label('Hospedaje')
                            ->columnSpan(1),
                    ])
                    ->columns(12)
                    ->collapsible()
                    ->columnSpan(12),
            ]);
    }
}
