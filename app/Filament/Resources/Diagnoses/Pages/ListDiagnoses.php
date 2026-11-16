<?php

namespace App\Filament\Resources\Diagnoses\Pages;

use App\Filament\Resources\Diagnoses\DiagnosisResource;
use Filament\Resources\Pages\ListRecords;

class ListDiagnoses extends ListRecords
{
    protected static string $resource = DiagnosisResource::class;
}
