<?php

namespace App\Filament\Resources\Piezas\Pages;

use App\Filament\Resources\Piezas\PiezaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPieza extends EditRecord
{
    protected static string $resource = PiezaResource::class;

    public function getTitle(): string
    {
        return 'Editar pieza';
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
