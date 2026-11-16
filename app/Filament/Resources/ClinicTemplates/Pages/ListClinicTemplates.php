<?php

namespace App\Filament\Resources\ClinicTemplates\Pages;

use App\Filament\Resources\ClinicTemplates\ClinicTemplatesResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListClinicTemplates extends ListRecords
{
    protected static string $resource = ClinicTemplatesResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->icon('heroicon-m-plus')];
    }

}
