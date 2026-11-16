<?php

namespace App\Filament\Resources\ClinicalNotes\Pages;

use App\Filament\Resources\ClinicalNotes\ClinicalNoteResource;
use Filament\Resources\Pages\ListRecords;

class ListClinicalNotes extends ListRecords
{
    protected static string $resource = ClinicalNoteResource::class;
}
