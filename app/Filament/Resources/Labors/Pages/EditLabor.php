<?php

namespace App\Filament\Resources\Labors\Pages;

use App\Filament\Resources\Labors\LaborResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLabor extends EditRecord
{
    protected static string $resource = LaborResource::class;

    public function getTitle(): string
    {
        return 'Editar mano de obra';
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
