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
                TextInput::make('pivot.cantidad')
                    ->label('Horas')
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
                    ->label('Tipo de mano de obra')
                    ->searchable(),
                TextColumn::make('pivot.cantidad')
                    ->label('Horas')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->using(function (RelationManager $livewire, Model $record, array $data): void {
                        $livewire->getOwnerRecord()->labores()->updateExistingPivot(
                            $record,
                            ['cantidad' => $data['pivot']['cantidad'] ?? $data['cantidad'] ?? 0]
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
