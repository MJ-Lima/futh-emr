<?php

namespace App\Filament\Resources\PresentingComplaints\Pages;

use App\Filament\Resources\PresentingComplaints\PresentingComplaintsResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListPresentingComplaints extends ListRecords
{
    protected static string $resource = PresentingComplaintsResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->icon('heroicon-m-plus')];
    }

}
