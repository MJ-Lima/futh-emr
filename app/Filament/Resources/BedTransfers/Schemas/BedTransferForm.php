<?php

namespace App\Filament\Resources\BedTransfers\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class BedTransferForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('BedTransfer Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('admission_id')->label('Admission')->required()->relationship('admission', 'admission_number')->searchable()->preload(),
                            Select::make('patient_id')->label('Patient')->required()->relationship('patient', 'hospital_no')->getOptionLabelUsing(fn ($record) => $record?->full_name ?? 'Unknown')->searchable()->preload(),
                            Select::make('from_ward_id')->label('From Ward')->relationship('from_ward', 'name')->searchable()->preload(),
                            Select::make('from_bed_id')->label('From Bed')->relationship('from_bed', 'bed_number')->searchable()->preload(),
                            Select::make('to_ward_id')->label('To Ward')->required()->relationship('to_ward', 'name')->searchable()->preload(),
                            Select::make('to_bed_id')->label('To Bed')->relationship('to_bed', 'bed_number')->searchable()->preload(),
                            DateTimePicker::make('transfer_date')->label('Transfer Date')->required(),
                            Textarea::make('reason')->label('Reason'),
                        ]),
                    ]),
            ]);
    }
}
