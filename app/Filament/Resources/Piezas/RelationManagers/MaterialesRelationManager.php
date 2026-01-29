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
use Filament\Forms\Components\Toggle;
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
                TextInput::make('cantidad')
                    ->label('Cantidad')
                    ->numeric()
                    ->step('0.01')
                    ->required()
                    ->helperText('Cantidad por metro de pieza'),
                Toggle::make('es_proporcional')
                    ->label('Es proporcional')
                    ->helperText('Si está activado, la cantidad se multiplicará por la medida de la zona (m, m2, etc.)')
                    ->default(true),
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
                TextColumn::make('cantidad')
                    ->label('Cantidad')
                    ->sortable(),
                TextColumn::make('es_proporcional')
                    ->label('Prop.')
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'warning')
                    ->formatStateUsing(fn($state) => $state ? 'Sí' : 'No'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Añadir material')
                    ->preloadRecordSelect()
                    ->form(fn(AttachAction $action) => [
                        $action->getRecordSelect(),
                        TextInput::make('cantidad')
                            ->label('Cantidad')
                            ->numeric()
                            ->step('0.01')
                            ->required()
                            ->helperText('Cantidad por metro de pieza'),
                        Toggle::make('es_proporcional')
                            ->label('Es proporcional')
                            ->helperText('Si está activado, la cantidad se multiplicará por la medida de la zona')
                            ->default(true),
                    ])
                    ->using(function (RelationManager $livewire, $record, array $data): void {
                        $livewire->getOwnerRecord()->materiales()->attach(
                            $record,
                            [
                                'cantidad' => $data['cantidad'],
                                'es_proporcional' => $data['es_proporcional'] ?? true,
                            ]
                        );
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->using(function (RelationManager $livewire, Model $record, array $data): void {
                        $livewire->getOwnerRecord()->materiales()->updateExistingPivot(
                            $record,
                            [
                                'cantidad' => $data['cantidad'] ?? 0,
                                'es_proporcional' => $data['es_proporcional'] ?? true,
                            ]
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
