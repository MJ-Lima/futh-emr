<?php

namespace App\Filament\Resources\DrugCategories\Pages;

use App\Filament\Resources\DrugCategories\DrugCategoryResource;
use Filament\Resources\Pages\ListRecords;

class ListDrugCategories extends ListRecords
{
    protected static string $resource = DrugCategoryResource::class;
}
