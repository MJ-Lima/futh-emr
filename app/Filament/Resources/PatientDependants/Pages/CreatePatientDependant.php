<?php

namespace App\Filament\Resources\PatientDependants\Pages;

use App\Filament\Resources\PatientDependants\PatientDependantResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePatientDependant extends CreateRecord
{
    protected static string $resource = PatientDependantResource::class;
}
