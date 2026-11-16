<?php

namespace App\Filament\Resources\GoodsReceivedNotes\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class GoodsReceivedNoteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('GoodsReceivedNote Information')
                    ->schema([
                        TextEntry::make('grn_number')->label('GRN #'),
                        TextEntry::make('supplier.name')->label('Supplier'),
                        TextEntry::make('received_at')->label('Received')->dateTime(),
                        TextEntry::make('invoice_number')->label('Invoice #'),
                        TextEntry::make('total_value')->label('Total Value'),
                        TextEntry::make('total_rejected')->label('Rejected'),
                        TextEntry::make('status')->label('Status'),
                        TextEntry::make('receiver.name')->label('Received By'),
                    ])
                    ->columns(2),
            ]);
    }
}
