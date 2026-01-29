<?php

namespace App\Filament\Resources\Piezas\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ManoDeObraRelationManager extends RelationManager
{
    protected static string $relationship = 'labores';

    public static function getTitle(?Model $ownerRecord = null, string $relationship = ''): string
    {
        return 'Mano de obra';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Tipo de mano de obra')
                    ->disabled(),
                TextInput::make('cantidad')
                    ->label('Minutos')
                    ->numeric()
                    ->step('0.01')
                    ->required()
                    ->helperText('Minutos fijos por pieza (no se multiplican por la medida de la zona)'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre')
            ->columns([
                TextColumn::make('nombre')
                    ->label('Tipo de mano de obra')
                    ->searchable(),
                TextColumn::make('cantidad')
                    ->label('Minutos')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('add_labor')
                    ->label('Añadir mano de obra de fabricación')
                    ->form([
                        TextInput::make('cantidad')
                            ->label('Minutos')
                            ->numeric()
                            ->step('0.01')
                            ->required()
                            ->helperText('Minutos por metro o unidad de pieza'),
                    ])
                    ->action(function (RelationManager $livewire, array $data): void {
                        // Buscar la labor de fabricación
                        $laborFabricacion = \App\Models\Labor::where('nombre', 'Mano de obra de fabricación')->first();

                        if (!$laborFabricacion) {
                            throw new \Exception('No se encontró la labor "Mano de obra de fabricación"');
                        }

                        // Verificar si ya existe
                        if ($livewire->getOwnerRecord()->labores()->where('labor_id', $laborFabricacion->id)->exists()) {
                            throw new \Exception('Esta pieza ya tiene mano de obra de fabricación asignada. Edítala en lugar de añadir una nueva.');
                        }

                        $livewire->getOwnerRecord()->labores()->attach(
                            $laborFabricacion->id,
                            [
                                'cantidad' => $data['cantidad'],
                            ]
                        );
                    })
                    ->successNotificationTitle('Mano de obra añadida correctamente'),
            ])
            ->recordActions([
                EditAction::make()
                    ->using(function (RelationManager $livewire, Model $record, array $data): void {
                        $livewire->getOwnerRecord()->labores()->updateExistingPivot(
                            $record,
                            [
                                'cantidad' => $data['cantidad'] ?? 0,
                            ]
                        );
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
