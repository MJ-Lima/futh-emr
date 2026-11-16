<?php

namespace App\Filament\Resources\InternalAudits\Pages;

use App\Filament\Resources\InternalAudits\InternalAuditResource;
use Filament\Resources\Pages\ListRecords;

class ListInternalAudits extends ListRecords
{
    protected static string $resource = InternalAuditResource::class;
}
