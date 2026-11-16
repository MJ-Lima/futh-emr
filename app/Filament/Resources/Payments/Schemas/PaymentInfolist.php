<?php

namespace App\Filament\Resources\Payments\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class PaymentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Payment Information')
                    ->schema([
                        TextEntry::make('payment_number')->label('Payment #'),
                        TextEntry::make('patient.full_name')->label('Patient'),
                        TextEntry::make('payer_type')->label('Payer'),
                        TextEntry::make('payment_method')->label('Method'),
                        TextEntry::make('amount')->label('Amount'),
                        TextEntry::make('payment_date')->label('Date')->dateTime(),
                        TextEntry::make('received_by.name')->label('Received By'),
                        TextEntry::make('status')->label('Status'),
                    ])
                    ->columns(2),
            ]);
    }
}
