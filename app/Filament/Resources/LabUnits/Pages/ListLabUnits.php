<?php

namespace App\Filament\Resources\LabUnits\Pages;

use App\Filament\Resources\LabUnits\LabUnitsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLabUnits extends ListRecords
{
    protected static string $resource = LabUnitsResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->icon('heroicon-m-plus')];
    }
}
