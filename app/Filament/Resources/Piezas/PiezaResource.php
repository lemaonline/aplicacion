<?php

namespace App\Filament\Resources\Piezas;

use App\Filament\Resources\Piezas\Pages\CreatePieza;
use App\Filament\Resources\Piezas\Pages\EditPieza;
use App\Filament\Resources\Piezas\Pages\ListPiezas;
use App\Filament\Resources\Piezas\RelationManagers\ManoDeObraRelationManager;
use App\Filament\Resources\Piezas\RelationManagers\MaterialesRelationManager;
use App\Filament\Resources\Piezas\Schemas\PiezaForm;
use App\Filament\Resources\Piezas\Tables\PiezasTable;
use App\Models\Pieza;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PiezaResource extends Resource
{
    protected static ?string $model = Pieza::class;

    protected static string|UnitEnum|null $navigationGroup = 'Proyectos';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'nombre';

    protected static ?string $navigationLabel = 'Piezas';

    protected static ?string $modelLabel = 'pieza';

    protected static ?string $pluralModelLabel = 'piezas';

    public static function form(Schema $schema): Schema
    {
        return PiezaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PiezasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            MaterialesRelationManager::class,
            ManoDeObraRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPiezas::route('/'),
            'create' => CreatePieza::route('/create'),
            'edit' => EditPieza::route('/{record}/edit'),
        ];
    }
}
