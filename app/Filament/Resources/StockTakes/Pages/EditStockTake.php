<?php

namespace App\Filament\Resources\StockTakes\Pages;

use App\Filament\Resources\StockTakes\StockTakeResource;
use Filament\Resources\Pages\EditRecord;

class EditStockTake extends EditRecord
{
    protected static string $resource = StockTakeResource::class;
}
