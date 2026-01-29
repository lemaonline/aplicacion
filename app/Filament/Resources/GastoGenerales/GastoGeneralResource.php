<?php

namespace App\Filament\Resources\GastoGenerales;

use App\Filament\Resources\GastoGenerales\Pages\ManageGastoGenerales;
use App\Models\GastoGeneral;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class GastoGeneralResource extends Resource
{
    protected static ?string $model = GastoGeneral::class;

    protected static string|UnitEnum|null $navigationGroup = 'Proyectos';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'nombre';

    protected static ?string $modelLabel = 'Gasto General';

    protected static ?string $pluralModelLabel = 'Gastos Generales';

    protected static ?string $navigationLabel = 'Gastos Generales';

    protected static ?string $slug = 'gastos-generales';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre/Concepto')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\Select::make('periodicidad')
                    ->options([
                        'mensual' => 'Mensual',
                        'trimestral' => 'Trimestral',
                        'semestral' => 'Semestral',
                        'anual' => 'Anual',
                    ])
                    ->required()
                    ->default('mensual'),
                TextInput::make('importe')
                    ->label('Importe (€)')
                    ->required()
                    ->numeric()
                    ->prefix('€'),
                Textarea::make('descripcion')
                    ->label('Descripción')
                    ->rows(2)
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre')
            ->columns([
                TextColumn::make('nombre')
                    ->label('Concepto')
                    ->searchable()
                    ->sortable()
                    ->description(fn(GastoGeneral $record): ?string => $record->descripcion),
                TextColumn::make('periodicidad')
                    ->label('Periodicidad')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)),
                TextColumn::make('importe')
                    ->label('Importe')
                    ->money('eur')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->actions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageGastoGenerales::route('/'),
        ];
    }
}
