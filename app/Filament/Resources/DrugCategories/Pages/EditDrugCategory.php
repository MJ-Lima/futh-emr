<?php

namespace App\Filament\Resources\DrugCategories\Pages;

use App\Filament\Resources\DrugCategories\DrugCategoryResource;
use Filament\Resources\Pages\EditRecord;

class EditDrugCategory extends EditRecord
{
    protected static string $resource = DrugCategoryResource::class;
}
