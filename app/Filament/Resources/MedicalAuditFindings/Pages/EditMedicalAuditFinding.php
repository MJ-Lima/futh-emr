<?php

namespace App\Filament\Resources\MedicalAuditFindings\Pages;

use App\Filament\Resources\MedicalAuditFindings\MedicalAuditFindingResource;
use Filament\Resources\Pages\EditRecord;

class EditMedicalAuditFinding extends EditRecord
{
    protected static string $resource = MedicalAuditFindingResource::class;
}
