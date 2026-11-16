<?php

namespace App\Filament\Resources\PatientSatisfactionSurveys\Pages;

use App\Filament\Resources\PatientSatisfactionSurveys\PatientSatisfactionSurveyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPatientSatisfactionSurveys extends ListRecords
{
    protected static string $resource = PatientSatisfactionSurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->icon('heroicon-m-plus')->label('New Survey')];
    }
}
