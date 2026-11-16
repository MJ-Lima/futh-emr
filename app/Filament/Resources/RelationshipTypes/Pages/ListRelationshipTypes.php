<?php

namespace App\Filament\Resources\RelationshipTypes\Pages;

use App\Filament\Resources\RelationshipTypes\RelationshipTypeResource;
use Filament\Resources\Pages\ListRecords;

class ListRelationshipTypes extends ListRecords
{
    protected static string $resource = RelationshipTypeResource::class;
}
