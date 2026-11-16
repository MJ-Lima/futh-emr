<?php

namespace App\Filament\Resources\BloodTransfusions\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class BloodTransfusionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('BloodTransfusion Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('patient_id')->label('Patient')->required()->relationship('patient', 'hospital_no')->getOptionLabelUsing(fn ($record) => $record?->full_name ?? 'Unknown')->searchable()->preload(),
                            Select::make('encounter_id')->label('Encounter')->relationship('encounter', 'encounter_number')->searchable()->preload(),
                            Select::make('admission_id')->label('Admission')->relationship('admission', 'admission_number')->searchable()->preload(),
                            Select::make('blood_request_id')->label('Blood Request')->required()->relationship('blood_request', 'request_number')->searchable()->preload(),
                            Select::make('blood_unit_id')->label('Blood Unit')->required()->relationship('blood_unit', 'unit_number')->searchable()->preload(),
                            Select::make('crossmatch_id')->label('Crossmatch')->relationship('crossmatch', 'crossmatch_number')->searchable()->preload(),
                            Select::make('transfused_by')->label('Transfused By')->required()->relationship('transfused_by', 'name')->searchable()->preload(),
                            Select::make('verified_by')->label('Verified By')->relationship('verified_by', 'name')->searchable()->preload(),
                            DateTimePicker::make('transfusion_started_at')->label('Started At')->required(),
                            DateTimePicker::make('transfusion_ended_at')->label('Ended At'),
                            TextInput::make('volume_transfused_ml')->label('Volume Transfused (ml)')->numeric(),
                            TextInput::make('site_of_transfusion')->label('Site of Transfusion'),
                            TextInput::make('cannula_size')->label('Cannula Size'),
                            TextInput::make('pre_transfusion_bp')->label('Pre-Transfusion BP')->numeric(),
                            TextInput::make('post_transfusion_bp')->label('Post-Transfusion BP')->numeric(),
                            TextInput::make('pre_transfusion_temp_c')->label('Pre-Transfusion Temp (°C)')->numeric(),
                            TextInput::make('post_transfusion_temp_c')->label('Post-Transfusion Temp (°C)')->numeric(),
                            TextInput::make('pre_transfusion_pulse')->label('Pre-Transfusion Pulse')->numeric(),
                            TextInput::make('post_transfusion_pulse')->label('Post-Transfusion Pulse')->numeric(),
                            Textarea::make('adverse_reactions')->label('Adverse Reactions'),
                            Select::make('reaction_severity')->label('Reaction Severity')->required()->options(['None' => 'None', 'Mild' => 'Mild', 'Moderate' => 'Moderate', 'Severe' => 'Severe', 'Fatal' => 'Fatal'])->searchable(),
                            Textarea::make('action_taken')->label('Action Taken'),
                            Select::make('status')->label('Status')->required()->options(['In Progress' => 'In Progress', 'Completed' => 'Completed', 'Interrupted' => 'Interrupted', 'Cancelled' => 'Cancelled'])->searchable(),
                            Textarea::make('notes')->label('Notes'),
                        ]),
                    ]),
            ]);
    }
}
