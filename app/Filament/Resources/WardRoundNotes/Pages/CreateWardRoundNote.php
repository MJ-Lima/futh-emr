<?php

namespace App\Filament\Resources\WardRoundNotes\Pages;

use App\Filament\Resources\WardRoundNotes\WardRoundNoteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWardRoundNote extends CreateRecord
{
    protected static string $resource = WardRoundNoteResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('view', ['record' => $this->record]);
    }
}
