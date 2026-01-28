<?php

namespace App\Filament\Resources\Materials\Pages;

use App\Filament\Resources\Materials\MaterialResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMaterials extends ListRecords
{
    protected static string $resource = MaterialResource::class;

    public function getTitle(): string
    {
        return 'Materiales';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Crear material'),
        ];
    }
}
