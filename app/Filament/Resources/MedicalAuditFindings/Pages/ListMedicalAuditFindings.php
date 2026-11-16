<?php

namespace App\Filament\Resources\MedicalAuditFindings\Pages;

use App\Filament\Resources\MedicalAuditFindings\MedicalAuditFindingResource;
use Filament\Resources\Pages\ListRecords;

class ListMedicalAuditFindings extends ListRecords
{
    protected static string $resource = MedicalAuditFindingResource::class;
}
