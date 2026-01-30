<?php

namespace App\Filament\Resources\Avances\Pages;

use App\Filament\Resources\Avances\AvanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAvances extends ListRecords
{
    protected static string $resource = AvanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
