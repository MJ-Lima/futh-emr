<?php

namespace App\Filament\Resources\MedicalAudits\Pages;

use App\Filament\Resources\MedicalAudits\MedicalAuditResource;
use Filament\Resources\Pages\ListRecords;

class ListMedicalAudits extends ListRecords
{
    protected static string $resource = MedicalAuditResource::class;
}
