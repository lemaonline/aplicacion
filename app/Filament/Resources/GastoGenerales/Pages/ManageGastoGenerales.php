<?php

namespace App\Filament\Resources\GastoGenerales\Pages;

use App\Filament\Resources\GastoGenerales\GastoGeneralResource;
use App\Livewire\GastoGeneralStats;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageGastoGenerales extends ManageRecords
{
    protected static string $resource = GastoGeneralResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            GastoGeneralStats::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
