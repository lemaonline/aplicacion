<?php

namespace App\Filament\Resources\Proveedors;

use App\Filament\Resources\Proveedors\Pages\CreateProveedor;
use App\Filament\Resources\Proveedors\Pages\EditProveedor;
use App\Filament\Resources\Proveedors\Pages\ListProveedors;
use App\Filament\Resources\Proveedors\Schemas\ProveedorForm;
use App\Filament\Resources\Proveedors\Tables\ProveedorsTable;
use App\Models\Proveedor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProveedorResource extends Resource
{
    protected static ?string $model = Proveedor::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-truck';

    protected static ?string $recordTitleAttribute = 'nombre';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return 'Administración';
    }

    public static function form(Schema $schema): Schema
    {
        return ProveedorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProveedorsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\Clientes\RelationManagers\ContactosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProveedors::route('/'),
            'create' => CreateProveedor::route('/create'),
            'edit' => EditProveedor::route('/{record}/edit'),
        ];
    }
}
