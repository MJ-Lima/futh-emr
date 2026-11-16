<?php

namespace App\Filament\Resources\RadiologyOrders\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class RadiologyOrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('RadiologyOrder Information')
                    ->schema([
                        TextEntry::make('order_number')->label('Order #'),
                        TextEntry::make('patient.full_name')->label('Patient'),
                        TextEntry::make('modality.name')->label('Modality'),
                        TextEntry::make('examination')->label('Examination'),
                        TextEntry::make('body_part')->label('Body Part'),
                        TextEntry::make('ordered_at')->label('Ordered')->dateTime(),
                        TextEntry::make('priority')->label('Priority'),
                        TextEntry::make('status')->label('Status'),
                    ])
                    ->columns(2),
            ]);
    }
}
