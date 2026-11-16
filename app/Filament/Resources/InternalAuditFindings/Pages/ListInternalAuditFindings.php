<?php

namespace App\Filament\Resources\InternalAuditFindings\Pages;

use App\Filament\Resources\InternalAuditFindings\InternalAuditFindingResource;
use Filament\Resources\Pages\ListRecords;

class ListInternalAuditFindings extends ListRecords
{
    protected static string $resource = InternalAuditFindingResource::class;
}
