<?php

namespace App\Filament\Resources\Facturas;

use App\Filament\Resources\Facturas\Pages\CreateFactura;
use App\Filament\Resources\Facturas\Pages\EditFactura;
use App\Filament\Resources\Facturas\Pages\ListFacturas;
use App\Filament\Resources\Facturas\Schemas\FacturaForm;
use App\Filament\Resources\Facturas\Tables\FacturasTable;
use App\Models\Factura;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FacturaResource extends Resource
{
    protected static ?string $model = Factura::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-currency-euro';

    protected static ?string $recordTitleAttribute = 'numero_factura';

    protected static ?int $navigationSort = 6;

    public static function getNavigationGroup(): ?string
    {
        return 'Administración';
    }

    public static function form(Schema $schema): Schema
    {
        return FacturaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FacturasTable::configure($table);
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
            'index' => ListFacturas::route('/'),
            'create' => CreateFactura::route('/create'),
            'edit' => EditFactura::route('/{record}/edit'),
        ];
    }
}
