<?php

namespace App\Filament\Resources\ResidentAdmissionNotes\Pages;

use App\Filament\Resources\ResidentAdmissionNotes\ResidentAdmissionNoteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateResidentAdmissionNote extends CreateRecord
{
    protected static string $resource = ResidentAdmissionNoteResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('view', ['record' => $this->record]);
    }
}
