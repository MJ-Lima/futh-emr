<?php

namespace App\Filament\Resources\Prescriptions\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class PrescriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Prescription Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('patient_id')->label('Patient')->required()->relationship('patient', 'hospital_no')->getOptionLabelUsing(fn ($record) => $record?->full_name ?? 'Unknown')->searchable()->preload(),
                            Select::make('encounter_id')->label('Encounter')->relationship('encounter', 'encounter_number')->searchable()->preload(),
                            Select::make('prescriber_id')->label('Prescriber')->required()->relationship('prescriber', 'name')->searchable()->preload(),
                            Select::make('dispensing_point_id')->label('Dispensing Point')->relationship('dispensingPoint', 'name')->searchable()->preload(),
                            DateTimePicker::make('prescribed_at')->label('Prescribed At')->required(),
                            Select::make('priority')->label('Priority')->required()->options(['Routine' => 'Routine', 'Urgent' => 'Urgent', 'STAT' => 'STAT'])->searchable(),
                            Toggle::make('is_inpatient')->label('Inpatient'),
                            Select::make('status')->label('Status')->required()->options(['Pending' => 'Pending', 'Partially Dispensed' => 'Partially Dispensed', 'Dispensed' => 'Dispensed', 'Cancelled' => 'Cancelled', 'Rejected' => 'Rejected'])->searchable(),
                            Textarea::make('notes')->label('Notes'),
                        ]),
                    ]),
            ]);
    }
}
