<?php

namespace App\Filament\Resources\WardRoundNotes\Pages;

use App\Filament\Resources\WardRoundNotes\WardRoundNoteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWardRoundNotes extends ListRecords
{
    protected static string $resource = WardRoundNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->icon('heroicon-m-plus')->label('New Ward Round Note')];
    }
}
