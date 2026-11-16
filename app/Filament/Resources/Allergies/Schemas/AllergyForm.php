<?php

namespace App\Filament\Resources\Allergies\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class AllergyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Allergy Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('patient_id')->label('Patient')->required()->relationship('patient', 'hospital_no')->getOptionLabelUsing(fn ($record) => $record?->full_name ?? 'Unknown')->searchable()->preload(),
                            Select::make('encounter_id')->label('Encounter')->relationship('encounter', 'encounter_number')->searchable()->preload(),
                            TextInput::make('substance')->label('Substance')->required(),
                            TextInput::make('code')->label('Code'),
                            Select::make('category')->label('Category')->required()->options(['Medication' => 'Medication', 'Food' => 'Food', 'Environment' => 'Environment', 'Biologic' => 'Biologic', 'Other' => 'Other'])->searchable(),
                            Select::make('criticality')->label('Criticality')->required()->options(['Low' => 'Low', 'High' => 'High', 'Unable to assess' => 'Unable to assess'])->searchable(),
                            Select::make('severity')->label('Severity')->required()->options(['Mild' => 'Mild', 'Moderate' => 'Moderate', 'Severe' => 'Severe', 'Fatal' => 'Fatal'])->searchable(),
                            Select::make('reaction')->label('Reaction')->required()->options(['Rash' => 'Rash', 'Urticaria' => 'Urticaria', 'Anaphylaxis' => 'Anaphylaxis', 'Angioedema' => 'Angioedema', 'Bronchospasm' => 'Bronchospasm', 'GI upset' => 'GI upset', 'Hepatotoxicity' => 'Hepatotoxicity', 'Stevens-Johnson' => 'Stevens-Johnson', 'Other' => 'Other'])->searchable(),
                            Textarea::make('reaction_description')->label('Reaction Description'),
                            DatePicker::make('onset_date')->label('Onset Date'),
                            Textarea::make('notes')->label('Notes'),
                            Select::make('status')->label('Status')->required()->options(['Active' => 'Active', 'Resolved' => 'Resolved', 'Inactive' => 'Inactive'])->searchable(),
                        ]),
                    ]),
            ]);
    }
}
