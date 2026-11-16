<?php

namespace App\Filament\Resources\PatientDependants\Pages;

use App\Filament\Resources\PatientDependants\PatientDependantResource;
use Filament\Resources\Pages\ListRecords;

class ListPatientDependants extends ListRecords
{
    protected static string $resource = PatientDependantResource::class;
}
