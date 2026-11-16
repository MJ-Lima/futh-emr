<?php

namespace App\Filament\Resources\Invoices\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Invoice Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('patient_id')->label('Patient')->required()->relationship('patient', 'hospital_no')->getOptionLabelUsing(fn ($record) => $record?->full_name ?? 'Unknown')->searchable()->preload(),
                            Select::make('encounter_id')->label('Encounter')->relationship('encounter', 'encounter_number')->searchable()->preload(),
                            Select::make('patient_insurance_id')->label('Insurance Policy')->relationship('policy', 'policy_number')->searchable()->preload(),
                            Select::make('payment_mode')->label('Payment Mode')->required()->options(['Cash' => 'Cash', 'Insurance' => 'Insurance', 'NHIA' => 'NHIA', 'Corporate' => 'Corporate', 'Charity' => 'Charity', 'Mixed' => 'Mixed'])->searchable(),
                            DatePicker::make('invoice_date')->label('Invoice Date')->required(),
                            DatePicker::make('due_date')->label('Due Date'),
                            TextInput::make('subtotal')->label('Subtotal')->numeric(),
                            TextInput::make('tax_amount')->label('Tax')->numeric(),
                            TextInput::make('discount_amount')->label('Discount')->numeric(),
                            TextInput::make('insurance_covered')->label('Insurance Covered')->numeric(),
                            TextInput::make('patient_portion')->label('Patient Portion')->numeric(),
                            TextInput::make('total_amount')->label('Total')->numeric(),
                            TextInput::make('amount_paid')->label('Amount Paid')->numeric(),
                            TextInput::make('balance_due')->label('Balance Due')->numeric(),
                            Select::make('status')->label('Status')->required()->options(['Draft' => 'Draft', 'Pending' => 'Pending', 'Partially Paid' => 'Partially Paid', 'Paid' => 'Paid', 'Overdue' => 'Overdue', 'Cancelled' => 'Cancelled', 'Refunded' => 'Refunded'])->searchable(),
                            Textarea::make('notes')->label('Notes'),
                        ]),
                    ]),
            ]);
    }
}
