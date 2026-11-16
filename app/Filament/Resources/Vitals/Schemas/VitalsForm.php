<?php

namespace App\Filament\Resources\Vitals\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class VitalsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Vitals Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('patient_id')->label('Patient')->required()->relationship('patient', 'hospital_no')->getOptionLabelUsing(fn ($record) => $record?->full_name ?? 'Unknown')->searchable()->preload(),
                            Select::make('encounter_id')->label('Encounter')->relationship('encounter', 'encounter_number')->searchable()->preload(),
                            DateTimePicker::make('recorded_at')->label('Recorded At')->required(),
                            TextInput::make('height_cm')->label('Height (cm)')->numeric(),
                            TextInput::make('weight_kg')->label('Weight (kg)')->numeric(),
                            TextInput::make('bmi')->label('BMI')->numeric(),
                            TextInput::make('systolic_bp')->label('Systolic BP')->numeric(),
                            TextInput::make('diastolic_bp')->label('Diastolic BP')->numeric(),
                            TextInput::make('heart_rate')->label('Heart Rate')->numeric(),
                            Select::make('bp_position')->label('BP Position')->options(['Sitting' => 'Sitting', 'Standing' => 'Standing', 'Supine' => 'Supine', 'Unknown' => 'Unknown'])->searchable(),
                            TextInput::make('respiratory_rate')->label('Resp Rate')->numeric(),
                            TextInput::make('spo2')->label('SpO2 (%)')->numeric(),
                            TextInput::make('temperature_c')->label('Temp (C)')->numeric(),
                            Select::make('temperature_route')->label('Temp Route')->options(['Oral' => 'Oral', 'Axillary' => 'Axillary', 'Tympanic' => 'Tympanic', 'Rectal' => 'Rectal', 'Forehead' => 'Forehead'])->searchable(),
                            TextInput::make('pain_score')->label('Pain Score')->numeric(),
                            TextInput::make('glasgow_coma_score')->label('GCS'),
                            TextInput::make('blood_glucose_mmol_l')->label('Glucose')->numeric(),
                            Select::make('glucose_fasting')->label('Glucose Type')->options(['Fasting' => 'Fasting', 'Random' => 'Random', 'Postprandial' => 'Postprandial', 'Unknown' => 'Unknown'])->searchable(),
                            TextInput::make('fetal_heart_rate')->label('Fetal HR')->numeric(),
                            TextInput::make('fundal_height_cm')->label('Fundal Height')->numeric(),
                            TextInput::make('muac_cm')->label('MUAC')->numeric(),
                            Textarea::make('notes')->label('Notes'),
                        ]),
                    ]),
            ]);
    }
}
