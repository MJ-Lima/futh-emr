<?php

namespace App\Filament\Resources\WardRoundNotes\Pages;

use App\Filament\Resources\WardRoundNotes\WardRoundNoteResource;
use Filament\Resources\Pages\ViewRecord;

class ViewWardRoundNote extends ViewRecord
{
    protected static string $resource = WardRoundNoteResource::class;

    public function getTitle(): string
    {
        return 'Ward Round Note · ' . $this->getRecord()->note_number;
    }
}
