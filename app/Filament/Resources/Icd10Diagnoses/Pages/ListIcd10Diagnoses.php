<?php

namespace App\Filament\Resources\Icd10Diagnoses\Pages;

use App\Filament\Resources\Icd10Diagnoses\Icd10DiagnosesResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListIcd10Diagnoses extends ListRecords
{
    protected static string $resource = Icd10DiagnosesResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->icon('heroicon-m-plus')];
    }

}
