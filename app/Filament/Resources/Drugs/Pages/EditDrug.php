<?php

namespace App\Filament\Resources\Drugs\Pages;

use App\Filament\Resources\Drugs\DrugResource;
use Filament\Resources\Pages\EditRecord;

class EditDrug extends EditRecord
{
    protected static string $resource = DrugResource::class;
}
