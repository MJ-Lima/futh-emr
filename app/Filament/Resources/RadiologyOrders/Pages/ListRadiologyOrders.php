<?php

namespace App\Filament\Resources\RadiologyOrders\Pages;

use App\Filament\Resources\RadiologyOrders\RadiologyOrderResource;
use Filament\Resources\Pages\ListRecords;

class ListRadiologyOrders extends ListRecords
{
    protected static string $resource = RadiologyOrderResource::class;
}
