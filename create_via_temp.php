<?php
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

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $modelLabel = 'Cliente';

    public static function getNavigationGroup(): ?string
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
PHP;

file_put_contents('C:/temp/ClienteResource/ClienteResource.php', $content);
echo "Written to temp: " . strlen($content) . " bytes\n";

// Copy to destination
$dest = 'app/Filament/Resources/Clientes/ClienteResource.php';
@mkdir(dirname($dest), 0755, true);
copy('C:/temp/ClienteResource/ClienteResource.php', $dest);
echo "Copied to $dest\n";

// Verify
$verify = file_get_contents($dest);
echo "Verification - starts with '<?php': " . (str_starts_with($verify, '<?php') ? 'YES' : 'NO') . "\n";
echo "Length after copy: " . strlen($verify) . "\n";
