<?php
$content = <<<'PHPCODE'
<?php

namespace App\Filament\Resources\Clientes;

use App\Filament\Resources\Clientes\Pages;
use App\Filament\Resources\Clientes\Schemas\ClienteForm;
use App\Filament\Resources\Clientes\Tables\ClienteTable;
use App\Models\Cliente;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use App\Filament\Resources\Clientes\RelationManagers;

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $modelLabel = 'Cliente';

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return 'CRM';
    }

    public static function form(Schema $schema): Schema
    {
        return ClienteForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClienteTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ContactosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClientes::route('/'),
            'create' => Pages\CreateCliente::route('/create'),
            'edit' => Pages\EditCliente::route('/{record}/edit'),
        ];
    }
}
PHPCODE;

// Delete and recreate
$file = 'app/Filament/Resources/Clientes/ClienteResource.php';
if (file_exists($file)) {
    unlink($file);
    clearstatcache(true, $file);
    sleep(1);
}

file_put_contents($file, $content);
clearstatcache(true, $file);

// Verify
$read = file_get_contents($file);
echo "Written: " . strlen($content) . " bytes\n";
echo "Read: " . strlen($read) . " bytes\n";
echo "Match: " . ($read === $content ? 'YES' : 'NO') . "\n";

if ($read !== $content) {
    echo "\n=== EXPECTED ===\n";
    echo substr($content, 0, 500);
    echo "\n\n=== ACTUAL ===\n";
    echo substr($read, 0, 500);
}
