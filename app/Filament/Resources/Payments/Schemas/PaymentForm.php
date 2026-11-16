<?php

namespace App\Filament\Resources\Payments\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Payment Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('patient_id')->label('Patient')->required()->relationship('patient', 'hospital_no')->getOptionLabelUsing(fn ($record) => $record?->full_name ?? 'Unknown')->searchable()->preload(),
                            Select::make('invoice_id')->label('Invoice')->relationship('invoice', 'invoice_number')->searchable()->preload(),
                            Select::make('insurance_claim_id')->label('Insurance Claim')->relationship('claim', 'claim_number')->searchable()->preload(),
                            Select::make('payer_type')->label('Payer Type')->required()->options(['Patient' => 'Patient', 'Insurance' => 'Insurance', 'NHIA' => 'NHIA', 'Corporate' => 'Corporate', 'Government' => 'Government', 'Other' => 'Other'])->searchable(),
                            TextInput::make('payer_name')->label('Payer Name'),
                            Select::make('payment_method')->label('Payment Method')->required()->options(['Cash' => 'Cash', 'Card' => 'Card', 'Bank Transfer' => 'Bank Transfer', 'Cheque' => 'Cheque', 'Mobile Money' => 'Mobile Money', 'POS' => 'POS', 'Insurance' => 'Insurance', 'Online' => 'Online'])->searchable(),
                            TextInput::make('amount')->label('Amount')->numeric(),
                            TextInput::make('reference_number')->label('Reference #'),
                            TextInput::make('bank_name')->label('Bank Name'),
                            TextInput::make('cheque_number')->label('Cheque #'),
                            TextInput::make('card_last4')->label('Card Last 4'),
                            DateTimePicker::make('payment_date')->label('Payment Date')->required(),
                            Select::make('status')->label('Status')->required()->options(['Pending' => 'Pending', 'Completed' => 'Completed', 'Reversed' => 'Reversed', 'Bounced' => 'Bounced'])->searchable(),
                            Textarea::make('notes')->label('Notes'),
                        ]),
                    ]),
            ]);
    }
}
