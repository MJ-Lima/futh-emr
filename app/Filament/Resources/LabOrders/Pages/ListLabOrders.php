<?php

namespace App\Filament\Resources\LabOrders\Pages;

use App\Filament\Resources\LabOrders\LabOrderResource;
use Filament\Resources\Pages\ListRecords;

class ListLabOrders extends ListRecords
{
    protected static string $resource = LabOrderResource::class;
}
