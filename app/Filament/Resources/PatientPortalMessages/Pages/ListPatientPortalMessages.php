<?php

namespace App\Filament\Resources\PatientPortalMessages\Pages;

use App\Filament\Resources\PatientPortalMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPatientPortalMessages extends ListRecords
{
    protected static string $resource = PatientPortalMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}