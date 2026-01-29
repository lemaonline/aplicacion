<?php

namespace App\Filament\Resources\Constantes\Pages;

use App\Filament\Resources\Constantes\ConstanteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageConstantes extends ManageRecords
{
    protected static string $resource = ConstanteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
