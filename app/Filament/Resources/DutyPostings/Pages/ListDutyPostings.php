<?php

namespace App\Filament\Resources\DutyPostings\Pages;

use App\Filament\Resources\DutyPostings\DutyPostingResource;
use Filament\Resources\Pages\ListRecords;

class ListDutyPostings extends ListRecords
{
    protected static string $resource = DutyPostingResource::class;
}
