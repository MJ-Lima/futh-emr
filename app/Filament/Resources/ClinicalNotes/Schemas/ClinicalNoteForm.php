<?php

namespace App\Filament\Resources\ClinicalNotes\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class ClinicalNoteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ClinicalNote Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('patient_id')->label('Patient')->required()->relationship('patient', 'hospital_no')->getOptionLabelUsing(fn ($record) => $record?->full_name ?? 'Unknown')->searchable()->preload(),
                            Select::make('encounter_id')->label('Encounter')->relationship('encounter', 'encounter_number')->searchable()->preload(),
                            Select::make('note_type')->label('Note Type')->required()->options(['Progress' => 'Progress', 'Admission' => 'Admission', 'Discharge' => 'Discharge', 'Nursing' => 'Nursing', 'Procedure' => 'Procedure', 'Consultation' => 'Consultation', 'Referral' => 'Referral', 'Death' => 'Death', 'Other' => 'Other'])->searchable(),
                            TextInput::make('title')->label('Title'),
                            Textarea::make('subjective')->label('Subjective (S)'),
                            Textarea::make('objective')->label('Objective (O)'),
                            Textarea::make('assessment')->label('Assessment (A)'),
                            Textarea::make('plan')->label('Plan (P)'),
                            RichEditor::make('narrative')->label('Narrative'),
                            TextInput::make('tags')->label('Tags'),
                        ]),
                    ]),
            ]);
    }
}
