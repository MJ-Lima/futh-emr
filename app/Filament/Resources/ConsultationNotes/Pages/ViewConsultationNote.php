<?php

namespace App\Filament\Resources\ConsultationNotes\Pages;

use App\Filament\Resources\ConsultationNotes\ConsultationNoteResource;
use Filament\Resources\Pages\ViewRecord;

class ViewConsultationNote extends ViewRecord
{
    protected static string $resource = ConsultationNoteResource::class;

    public function getTitle(): string
    {
        return 'Consultation Note · ' . $this->getRecord()->note_number;
    }
}
