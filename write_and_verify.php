<?php
// Write file fresh
$content = <<<'PHP'
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
use UnitEnum;

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $modelLabel = 'Cliente';
    
    protected static string|UnitEnum|null $navigationGroup = 'CRM';

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
PHP;

$file = 'app/Filament/Resources/Clientes/ClienteResource.php';

// Clear any stat cache
clearstatcache(true, $file);

// Delete and recreate
if (file_exists($file)) {
    unlink($file);
}
sleep(1); // wait a second

file_put_contents($file, $content);
clearstatcache(true, $file);

echo "File written: " . strlen($content) . " bytes\n";

// Read it back
$read = file_get_contents($file);
echo "File read: " . strlen($read) . " bytes\n";
echo "Content matches: " . ($read === $content ? 'YES' : 'NO') . "\n";

if ($read !== $content) {
    echo "\nExpected first 100 chars:\n" . substr($content, 0, 100) . "\n";
    echo "\nActual first 100 chars:\n" . substr($read, 0, 100) . "\n";
}
