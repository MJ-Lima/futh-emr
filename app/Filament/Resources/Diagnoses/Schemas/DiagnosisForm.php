<?php

namespace App\Filament\Resources\Diagnoses\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class DiagnosisForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Diagnosis Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('patient_id')->label('Patient')->required()->relationship('patient', 'hospital_no')->getOptionLabelUsing(fn ($record) => $record?->full_name ?? 'Unknown')->searchable()->preload(),
                            Select::make('encounter_id')->label('Encounter')->relationship('encounter', 'encounter_number')->searchable()->preload(),
                            TextInput::make('icd10_code')->label('ICD-10 Code')->required(),
                            TextInput::make('description')->label('Description')->required(),
                            Select::make('type')->label('Type')->required()->options(['Principal' => 'Principal', 'Admitting' => 'Admitting', 'Working' => 'Working', 'Discharge' => 'Discharge', 'Secondary' => 'Secondary', 'Comorbidity' => 'Comorbidity'])->searchable(),
                            Select::make('status')->label('Status')->required()->options(['Active' => 'Active', 'Resolved' => 'Resolved', 'Chronic' => 'Chronic', 'Recurrent' => 'Recurrent'])->searchable(),
                            DatePicker::make('onset_date')->label('Onset Date'),
                            DatePicker::make('resolved_date')->label('Resolved Date'),
                            Textarea::make('notes')->label('Notes'),
                        ]),
                    ]),
            ]);
    }
}
