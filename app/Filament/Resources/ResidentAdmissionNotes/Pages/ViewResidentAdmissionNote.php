<?php

namespace App\Filament\Resources\ResidentAdmissionNotes\Pages;

use App\Filament\Resources\ResidentAdmissionNotes\ResidentAdmissionNoteResource;
use Filament\Resources\Pages\ViewRecord;

class ViewResidentAdmissionNote extends ViewRecord
{
    protected static string $resource = ResidentAdmissionNoteResource::class;

    public function getTitle(): string
    {
        return 'Resident Admission Note · ' . $this->getRecord()->note_number;
    }
}
