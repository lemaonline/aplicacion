<?php

namespace App\Filament\Resources\Constantes;

use App\Filament\Resources\Constantes\Pages\ManageConstantes;
use App\Models\Constante;
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

class ConstanteResource extends Resource
{
    protected static ?string $model = Constante::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $recordTitleAttribute = 'nombre';

    protected static ?string $modelLabel = 'Constante';

    protected static ?string $pluralModelLabel = 'Constantes';

    protected static ?string $navigationLabel = 'Constantes';

    protected static ?string $slug = 'constantes';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('valor')
                    ->label('Valor (€)')
                    ->required()
                    ->numeric()
                    ->prefix('€')
                    ->step(0.01),
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
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->description(fn(Constante $record): ?string => $record->descripcion),
                TextColumn::make('valor')
                    ->label('Valor')
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
            'index' => ManageConstantes::route('/'),
        ];
    }
}
