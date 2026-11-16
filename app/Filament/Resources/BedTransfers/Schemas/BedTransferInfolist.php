<?php

namespace App\Filament\Resources\BedTransfers\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class BedTransferInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('BedTransfer Information')
                    ->schema([
                        TextEntry::make('transfer_number')->label('Transfer #'),
                        TextEntry::make('patient.full_name')->label('Patient'),
                        TextEntry::make('fromWard.name')->label('From Ward'),
                        TextEntry::make('fromBed.bed_number')->label('From Bed'),
                        TextEntry::make('toWard.name')->label('To Ward'),
                        TextEntry::make('toBed.bed_number')->label('To Bed'),
                        TextEntry::make('transfer_date')->label('Date')->dateTime(),
                        TextEntry::make('authorized_by.name')->label('Authorized By'),
                    ])
                    ->columns(2),
            ]);
    }
}
