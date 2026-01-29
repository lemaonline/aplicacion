<?php
// Create Clientes directory structure
$base = 'app/Filament/Resources/Clientes';
@mkdir($base, 0755, true);
@mkdir("$base/Pages", 0755, true);
@mkdir("$base/Schemas", 0755, true);
@mkdir("$base/Tables", 0755, true);
@mkdir("$base/RelationManagers", 0755, true);

// ClienteResource.php
$resource = <<<'PHP'
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
file_put_contents("$base/ClienteResource.php", $resource);

// Pages
$listClientes = <<<'PHP'
<?php

namespace App\Filament\Resources\Clientes\Pages;

use App\Filament\Resources\Clientes\ClienteResource;
use Filament\Resources\Pages\ListRecords;

class ListClientes extends ListRecords
{
    protected static string $resource = ClienteResource::class;
}
PHP;
file_put_contents("$base/Pages/ListClientes.php", $listClientes);

$createCliente = <<<'PHP'
<?php

namespace App\Filament\Resources\Clientes\Pages;

use App\Filament\Resources\Clientes\ClienteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCliente extends CreateRecord
{
    protected static string $resource = ClienteResource::class;
}
PHP;
file_put_contents("$base/Pages/CreateCliente.php", $createCliente);

$editCliente = <<<'PHP'
<?php

namespace App\Filament\Resources\Clientes\Pages;

use App\Filament\Resources\Clientes\ClienteResource;
use Filament\Resources\Pages\EditRecord;

class EditCliente extends EditRecord
{
    protected static string $resource = ClienteResource::class;
}
PHP;
file_put_contents("$base/Pages/EditCliente.php", $editCliente);

// Schemas/ClienteForm.php
$clienteForm = <<<'PHP'
<?php

namespace App\Filament\Resources\Clientes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ClienteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('cif')
                    ->label('CIF/NIF')
                    ->unique(ignoreRecord: true)
                    ->maxLength(20),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                TextInput::make('telefono')
                    ->tel()
                    ->maxLength(20),
                TextInput::make('direccion')
                    ->label('Direccion')
                    ->maxLength(255),
                Textarea::make('observaciones')
                    ->maxLength(65535),
                Toggle::make('activo')
                    ->default(true)
                    ->required(),
            ]);
    }
}
PHP;
file_put_contents("$base/Schemas/ClienteForm.php", $clienteForm);

// Tables/ClienteTable.php
$clienteTable = <<<'PHP'
<?php

namespace App\Filament\Resources\Clientes\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;

class ClienteTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cif')
                    ->label('CIF/NIF')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('telefono'),
                IconColumn::make('activo')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
PHP;
file_put_contents("$base/Tables/ClienteTable.php", $clienteTable);

// RelationManagers/ContactosRelationManager.php
$contactosRM = <<<'PHP'
<?php

namespace App\Filament\Resources\Clientes\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class ContactosRelationManager extends RelationManager
{
    protected static string $relationship = 'contactos';

    protected static ?string $recordTitleAttribute = 'nombre';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                TextInput::make('telefono')
                    ->tel()
                    ->maxLength(20),
                Toggle::make('activo')
                    ->default(true)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('telefono'),
                Tables\Columns\IconColumn::make('activo')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
PHP;
file_put_contents("$base/RelationManagers/ContactosRelationManager.php", $contactosRM);

echo "All Clientes files created successfully!\n";
