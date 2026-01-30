<?php

namespace App\Filament\Resources\Avances;

use App\Filament\Resources\Avances\Pages\CreateAvance;
use App\Filament\Resources\Avances\Pages\EditAvance;
use App\Filament\Resources\Avances\Pages\ListAvances;
use App\Filament\Resources\Avances\Schemas\AvanceForm;
use App\Filament\Resources\Avances\Tables\AvancesTable;
use App\Models\Avance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AvanceResource extends Resource
{
    protected static ?string $model = Avance::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?string $recordTitleAttribute = null;

    public static function getRecordTitle(?\Illuminate\Database\Eloquent\Model $record): ?string
    {
        return $record->obra->nombre . ' - ' . $record->fecha->format('d/m/Y');
    }

    protected static ?int $navigationSort = 5;

    public static function getNavigationGroup(): ?string
    {
        return 'Administración';
    }

    public static function getNavigationLabel(): string
    {
        return 'Avances de Obra';
    }

    public static function form(Schema $schema): Schema
    {
        return AvanceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AvancesTable::configure($table);
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
            'index' => ListAvances::route('/'),
            'create' => CreateAvance::route('/create'),
            'edit' => EditAvance::route('/{record}/edit'),
        ];
    }
}
