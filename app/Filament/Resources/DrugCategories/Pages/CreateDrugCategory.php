<?php

namespace App\Filament\Resources\DrugCategories\Pages;

use App\Filament\Resources\DrugCategories\DrugCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDrugCategory extends CreateRecord
{
    protected static string $resource = DrugCategoryResource::class;
}
