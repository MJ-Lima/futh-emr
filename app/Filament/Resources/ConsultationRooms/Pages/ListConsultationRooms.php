<?php

namespace App\Filament\Resources\ConsultationRooms\Pages;

use App\Filament\Resources\ConsultationRooms\ConsultationRoomResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListConsultationRooms extends ListRecords
{
    protected static string $resource = ConsultationRoomResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->icon('heroicon-m-plus')->label('New Room')];
    }
}
