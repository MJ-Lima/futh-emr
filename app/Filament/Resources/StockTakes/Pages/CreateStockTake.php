<?php

namespace App\Filament\Resources\StockTakes\Pages;

use App\Filament\Resources\StockTakes\StockTakeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStockTake extends CreateRecord
{
    protected static string $resource = StockTakeResource::class;
}
