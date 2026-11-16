<?php

namespace App\Filament\Resources\InternalAuditFindings\Pages;

use App\Filament\Resources\InternalAuditFindings\InternalAuditFindingResource;
use Filament\Resources\Pages\EditRecord;

class EditInternalAuditFinding extends EditRecord
{
    protected static string $resource = InternalAuditFindingResource::class;
}
