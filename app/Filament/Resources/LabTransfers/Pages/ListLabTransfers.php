<?php

namespace App\Filament\Resources\LabTransfers\Pages;

use App\Filament\Resources\LabTransfers\LabTransfersResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLabTransfers extends ListRecords
{
    protected static string $resource = LabTransfersResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->icon('heroicon-m-plus')];
    }
}
