<?php

namespace App\Filament\Resources\GoodsReceivedNotes\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class GoodsReceivedNoteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('GoodsReceivedNote Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('purchase_id')->label('Purchase Order')->relationship('purchase', 'purchase_number')->searchable()->preload(),
                            Select::make('supplier_id')->label('Supplier')->relationship('supplier', 'name')->searchable()->preload(),
                            Select::make('receipt_id')->label('Receipt')->relationship('receipt', 'receipt_number')->searchable()->preload(),
                            Select::make('dispensing_point_id')->label('Dispensing Point')->relationship('dispensing_point', 'name')->searchable()->preload(),
                            DateTimePicker::make('received_at')->label('Received At')->required(),
                            TextInput::make('invoice_number')->label('Invoice #'),
                            TextInput::make('delivery_note_number')->label('Delivery Note #'),
                            TextInput::make('lpo_number')->label('LPO #'),
                            DatePicker::make('invoice_date')->label('Invoice Date'),
                            TextInput::make('total_value')->label('Total Value')->numeric(),
                            TextInput::make('total_rejected')->label('Total Rejected')->numeric(),
                            Select::make('status')->label('Status')->required()->options(['Pending' => 'Pending', 'Verified' => 'Verified', 'Rejected' => 'Rejected', 'Posted' => 'Posted'])->searchable(),
                            Textarea::make('notes')->label('Notes'),
                        ]),
                    ]),
            ]);
    }
}
