<?php

namespace App\Filament\Resources\ResidentAdmissionNotes\Pages;

use App\Filament\Resources\ResidentAdmissionNotes\ResidentAdmissionNoteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListResidentAdmissionNotes extends ListRecords
{
    protected static string $resource = ResidentAdmissionNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->icon('heroicon-m-plus')->label('New Resident Note')];
    }
}
