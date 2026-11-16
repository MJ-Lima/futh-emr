<?php

namespace App\Filament\Resources\ClinicalNotes\Pages;

use App\Filament\Resources\ClinicalNotes\ClinicalNoteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClinicalNote extends CreateRecord
{
    protected static string $resource = ClinicalNoteResource::class;
}
