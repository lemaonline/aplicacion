<?php

namespace App\Filament\Resources\Piezas\Pages;

use App\Filament\Resources\Piezas\PiezaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPiezas extends ListRecords
{
    protected static string $resource = PiezaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
