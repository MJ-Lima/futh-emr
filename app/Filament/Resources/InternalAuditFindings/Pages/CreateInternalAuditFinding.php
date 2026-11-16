<?php

namespace App\Filament\Resources\InternalAuditFindings\Pages;

use App\Filament\Resources\InternalAuditFindings\InternalAuditFindingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInternalAuditFinding extends CreateRecord
{
    protected static string $resource = InternalAuditFindingResource::class;
}
