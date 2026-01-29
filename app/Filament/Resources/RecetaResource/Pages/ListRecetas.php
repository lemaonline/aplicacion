<?php

namespace App\Filament\Resources\RecetaResource\Pages;

use App\Filament\Resources\RecetaResource;
use Filament\Resources\Pages\ListRecords;

class ListRecetas extends ListRecords
{
    protected static string $resource = RecetaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
