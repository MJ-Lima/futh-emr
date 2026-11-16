<?php

namespace App\Filament\Resources\StoreRequisitions\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class StoreRequisitionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('StoreRequisition Information')
                    ->schema([
                        TextEntry::make('requisition_number')->label('Req #'),
                        TextEntry::make('requestingDepartment.name')->label('Dept'),
                        TextEntry::make('sourceLocation.name')->label('From Store'),
                        TextEntry::make('required_date')->label('Required')->date(),
                        TextEntry::make('priority')->label('Priority'),
                        TextEntry::make('status')->label('Status'),
                        TextEntry::make('requester.name')->label('By'),
                    ])
                    ->columns(2),
            ]);
    }
}
