<?php

namespace App\Filament\Resources\ConsultationNotes\Pages;

use App\Filament\Resources\ConsultationNotes\ConsultationNoteResource;
use Filament\Resources\Pages\EditRecord;

class EditConsultationNote extends EditRecord
{
    protected static string $resource = ConsultationNoteResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('view', ['record' => $this->record]);
    }
}
