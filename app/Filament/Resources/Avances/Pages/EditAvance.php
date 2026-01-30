<?php

namespace App\Filament\Resources\Avances\Pages;

use App\Filament\Resources\Avances\AvanceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAvance extends EditRecord
{
    protected static string $resource = AvanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
