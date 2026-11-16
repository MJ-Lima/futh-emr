<?php

namespace App\Filament\Resources\StoreItems\Pages;

use App\Filament\Resources\StoreItems\StoreItemResource;
use Filament\Resources\Pages\ListRecords;

class ListStoreItems extends ListRecords
{
    protected static string $resource = StoreItemResource::class;
}
