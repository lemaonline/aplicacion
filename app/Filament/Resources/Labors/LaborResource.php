<?php

namespace App\Filament\Resources\Labors;

use App\Filament\Resources\Labors\Pages\CreateLabor;
use App\Filament\Resources\Labors\Pages\EditLabor;
use App\Filament\Resources\Labors\Pages\ListLabors;
use App\Filament\Resources\Labors\Schemas\LaborForm;
use App\Filament\Resources\Labors\Tables\LaborsTable;
use App\Models\Labor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LaborResource extends Resource
{
    protected static ?string $model = Labor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Mano de Obra';

    protected static ?string $modelLabel = 'mano de obra';

    protected static ?string $pluralModelLabel = 'mano de obra';

    protected static ?string $recordTitleAttribute = 'nombre';

    public static function form(Schema $schema): Schema
    {
        return LaborForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LaborsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLabors::route('/mano-de-obra'),
            'create' => CreateLabor::route('/mano-de-obra/crear'),
            'edit' => EditLabor::route('/mano-de-obra/{record}/editar'),
        ];
    }
}
