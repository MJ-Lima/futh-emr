<?php

namespace App\Filament\Resources\DischargeSummaries\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class DischargeSummaryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('DischargeSummary Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('admission_id')->label('Admission')->required()->relationship('admission', 'admission_number')->searchable()->preload(),
                            Select::make('patient_id')->label('Patient')->required()->relationship('patient', 'hospital_no')->getOptionLabelUsing(fn ($record) => $record?->full_name ?? 'Unknown')->searchable()->preload(),
                            Select::make('discharging_doctor_id')->label('Discharging Doctor')->required()->relationship('discharging_doctor', 'name')->searchable()->preload(),
                            DateTimePicker::make('discharge_date')->label('Discharge Date')->required(),
                            Select::make('discharge_type')->label('Discharge Type')->required()->options(['Improved' => 'Improved', 'Recovered' => 'Recovered', 'Refused Treatment' => 'Refused Treatment', 'Absconded' => 'Absconded', 'Transferred' => 'Transferred', 'Death' => 'Death', 'AMA' => 'AMA', 'Other' => 'Other'])->searchable(),
                            Textarea::make('final_diagnosis')->label('Final Diagnosis'),
                            Textarea::make('comorbidities')->label('Comorbidities'),
                            Textarea::make('procedures_performed')->label('Procedures Performed'),
                            Textarea::make('investigations_summary')->label('Investigations Summary'),
                            Textarea::make('treatment_summary')->label('Treatment Summary'),
                            Textarea::make('treatment_given')->label('Treatment Given'),
                            Textarea::make('condition_on_discharge')->label('Condition on Discharge'),
                            Textarea::make('medications_on_discharge')->label('Medications on Discharge'),
                            Textarea::make('follow_up_instructions')->label('Follow-up Instructions'),
                            DatePicker::make('follow_up_date')->label('Follow-up Date'),
                            Textarea::make('death_cause')->label('Cause of Death (if applicable)'),
                            TimePicker::make('death_time')->label('Time of Death'),
                            Textarea::make('notes')->label('Notes'),
                        ]),
                    ]),
            ]);
    }
}
