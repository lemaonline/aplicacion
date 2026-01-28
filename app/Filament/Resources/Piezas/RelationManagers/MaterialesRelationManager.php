<?php

namespace App\Filament\Resources\Piezas\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class MaterialesRelationManager extends RelationManager
{
    protected static string $relationship = 'materiales';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Material')
                    ->disabled(),
                TextInput::make('pivot.cantidad')
                    ->label('Cantidad')
                    ->numeric()
                    ->step('0.01')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre')
            ->columns([
                TextColumn::make('nombre')
                    ->label('Material')
                    ->searchable(),
                TextColumn::make('pivot.cantidad')
                    ->label('Cantidad')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Añadir material')
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action) => [
                        $action->getRecordSelect(),
                        TextInput::make('cantidad')
                            ->label('Cantidad')
                            ->numeric()
                            ->step('0.01')
                            ->required(),
                    ])
                    ->using(function (RelationManager $livewire, $record, array $data): void {
                        $livewire->getOwnerRecord()->materiales()->attach(
                            $record,
                            ['cantidad' => $data['cantidad']]
                        );
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->using(function (RelationManager $livewire, Model $record, array $data): void {
                        $livewire->getOwnerRecord()->materiales()->updateExistingPivot(
                            $record,
                            ['cantidad' => $data['pivot']['cantidad'] ?? $data['cantidad'] ?? 0]
                        );
                    }),
                DetachAction::make()
                    ->label('Desvincular'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
