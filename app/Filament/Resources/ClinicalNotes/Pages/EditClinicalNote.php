<?php

namespace App\Filament\Resources\ClinicalNotes\Pages;

use App\Filament\Resources\ClinicalNotes\ClinicalNoteResource;
use Filament\Resources\Pages\EditRecord;

class EditClinicalNote extends EditRecord
{
    protected static string $resource = ClinicalNoteResource::class;
}
