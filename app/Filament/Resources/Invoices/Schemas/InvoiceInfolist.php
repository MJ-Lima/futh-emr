<?php

namespace App\Filament\Resources\Invoices\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class InvoiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Invoice Information')
                    ->schema([
                        TextEntry::make('invoice_number')->label('Invoice #'),
                        TextEntry::make('patient.full_name')->label('Patient'),
                        TextEntry::make('payment_mode')->label('Mode'),
                        TextEntry::make('invoice_date')->label('Date')->date(),
                        TextEntry::make('total_amount')->label('Total'),
                        TextEntry::make('amount_paid')->label('Paid'),
                        TextEntry::make('balance_due')->label('Balance'),
                        TextEntry::make('status')->label('Status'),
                    ])
                    ->columns(2),
            ]);
    }
}
