<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecetaResource\Pages;
use App\Models\Receta;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class RecetaResource extends Resource
{
    protected static ?string $model = Receta::class;

    protected static string|UnitEnum|null $navigationGroup = 'Proyectos';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationLabel = 'Recetas (Campos)';

    protected static ?string $modelLabel = 'receta';

    protected static ?string $pluralModelLabel = 'recetas';

    public static function getRecordTitle(?Model $record): ?string
    {
        return $record ? "Receta: " . self::getCampoLabel($record->campo_nombre) : null;
    }

    protected static function getCampoLabel(string $campo): string
    {
        $campos = [
            'wp_500' => 'WP 500',
            'wp_250' => 'WP 250',
            'chapa_galva' => 'Chapa Galva',
            'puerta_1000' => 'Puerta 1000',
            'puerta_750' => 'Puerta 750',
            'puerta_1500' => 'Puerta 1500',
            'puerta_2000' => 'Puerta 2000',
            'twin_750' => 'Twin 750',
            'twin_1000' => 'Twin 1000',
            'malla_techo' => 'Malla Techo',
            'tablero' => 'Tablero',
            'esquinas' => 'Esquinas',
            'extra_galva' => 'Extra Galva',
            'extra_wp' => 'Extra WP',
            'extra_damero' => 'Extra Damero',
            'num_trasteros' => 'Nº Trasteros',
            'pasillos' => 'Punto de Pasillos',
        ];

        return $campos[$campo] ?? $campo;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Select::make('campo_nombre')
                    ->label('Nombre del Campo')
                    ->options([
                        'wp_500' => 'WP 500',
                        'wp_250' => 'WP 250',
                        'chapa_galva' => 'Chapa Galva',
                        'puerta_1000' => 'Puerta 1000',
                        'puerta_750' => 'Puerta 750',
                        'puerta_1500' => 'Puerta 1500',
                        'puerta_2000' => 'Puerta 2000',
                        'twin_750' => 'Twin 750',
                        'twin_1000' => 'Twin 1000',
                        'roller_750' => 'Roller 750',
                        'roller_1000' => 'Roller 1000',
                        'roller_1500' => 'Roller 1500',
                        'malla_techo' => 'Malla Techo',
                        'tablero' => 'Tablero',
                        'esquinas' => 'Esquinas',
                        'extra_galva' => 'Extra Galva',
                        'extra_wp' => 'Extra WP',
                        'extra_damero' => 'Extra Damero',
                        'num_trasteros' => 'Nº Trasteros',
                        'pasillos' => 'Punto de Pasillos',
                    ])
                    ->required()
                    ->searchable()
                    ->live()
                    ->unique(ignoreRecord: true),

                // Se eliminan las condiciones de aquí (nivel receta)

                Repeater::make('items')
                    ->relationship('items')
                    ->schema([
                        Select::make('pieza_id')
                            ->label('Pieza/Material')
                            ->relationship('pieza', 'nombre')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->columnSpan(3),

                        TextInput::make('cantidad_base')
                            ->label('Cant. Base')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->step(0.0001)
                            ->columnSpan(1),

                        Select::make('referencia')
                            ->label('Factor de Escala (Legacy)')
                            ->options([
                                'unidad' => 'Fijo (1.0)',
                                'altura' => 'Escalar por Altura (HS)',
                                'ancho_pasillo' => 'Escalar por Ancho Pasillo (AP)',
                                'altura_puerta' => 'Escalar por Altura Puerta (HP)',
                            ])
                            ->helperText('Solo se aplica si la fórmula está vacía.')
                            ->default('unidad')
                            ->required()
                            ->columnSpan(2),

                        TextInput::make('formula')
                            ->label('Fórmula Matemática')
                            ->placeholder('Ej: ({HS}-{HP})/1000')
                            ->helperText('Placeholders: {VAL} (valor campo), {HS} (Alt.Sist), {HP} (Alt.Puerta), {M2} (Superficie), {TR} (Trasteros), {AP} (Ancho Pasillo). Todo en mm, excepto M2 y AP que son metros.')
                            ->columnSpan(4),

                        Grid::make(2)
                            ->schema([
                                Select::make('condicion_cerradura')
                                    ->label('Cerradura')
                                    ->placeholder('Cualquiera')
                                    ->options([
                                        'normal' => 'Normal',
                                        'automatica' => 'Automática',
                                    ])
                                    ->columnSpan(1),

                                Select::make('condicion_bisagra')
                                    ->label('Bisagra')
                                    ->placeholder('Cualquiera')
                                    ->options([
                                        'normal' => 'Normal',
                                        'muelle' => 'Con Muelle',
                                    ])
                                    ->columnSpan(1),
                            ])
                            ->visible(fn(Get $get) => str_starts_with($get('../../campo_nombre') ?? '', 'puerta_') ||
                                str_starts_with($get('../../campo_nombre') ?? '', 'twin_') ||
                                str_starts_with($get('../../campo_nombre') ?? '', 'roller_'))
                            ->columnSpan(6),
                    ])
                    ->columns(6)
                    ->label('Piezas de la Receta')
                    ->addActionLabel('Añadir Pieza')
                    ->collapsible()
                    ->cloneable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('campo_nombre')
                    ->label('Campo')
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(fn($state) => self::getCampoLabel($state)),

                TextColumn::make('items_count')
                    ->label('Nº Piezas')
                    ->counts('items'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecetas::route('/'),
            'create' => Pages\CreateReceta::route('/create'),
            'edit' => Pages\EditReceta::route('/{record}/edit'),
        ];
    }
}
