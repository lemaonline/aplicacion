<?php

namespace App\Filament\Resources\Labors\Pages;

use App\Filament\Resources\Labors\LaborResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLabors extends ListRecords
{
    protected static string $resource = LaborResource::class;

    public function getTitle(): string
    {
        return 'Mano de Obra';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Crear mano de obra'),
        ];
    }
}
