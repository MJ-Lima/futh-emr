<?php

namespace App\Filament\Resources\Drugs\Pages;

use App\Filament\Resources\Drugs\DrugResource;
use Filament\Resources\Pages\ListRecords;

class ListDrugs extends ListRecords
{
    protected static string $resource = DrugResource::class;
}
