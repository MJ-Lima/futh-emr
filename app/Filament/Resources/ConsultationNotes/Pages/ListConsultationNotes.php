<?php

namespace App\Filament\Resources\ConsultationNotes\Pages;

use App\Filament\Resources\ConsultationNotes\ConsultationNoteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListConsultationNotes extends ListRecords
{
    protected static string $resource = ConsultationNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->icon('heroicon-m-plus')->label('New Consultation Note')];
    }
}
