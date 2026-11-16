<?php

namespace App\Filament\Resources\Allergies\Pages;

use App\Filament\Resources\Allergies\AllergyResource;
use Filament\Resources\Pages\ListRecords;

class ListAllergies extends ListRecords
{
    protected static string $resource = AllergyResource::class;
}
