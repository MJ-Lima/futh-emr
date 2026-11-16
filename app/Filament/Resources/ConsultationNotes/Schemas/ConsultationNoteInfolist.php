<?php

namespace App\Filament\Resources\ConsultationNotes\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class ConsultationNoteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Encounter & Author')
                ->icon('heroicon-m-link')
                ->schema([
                    TextEntry::make('note_number')->label('Note #')->badge()->color('info')->copyable(),
                    TextEntry::make('encounter.encounter_number')->label('Encounter #'),
                    TextEntry::make('patient.full_name')->label('Patient'),
                    TextEntry::make('clinic.name')->label('Clinic')->badge(),
                    TextEntry::make('author.name')->label('Author'),
                    TextEntry::make('status')->label('Status')->badge()
                        ->color(fn ($s) => \App\Models\ConsultationNote::STATUS_COLORS[$s] ?? 'gray'),
                    TextEntry::make('countersigner.name')->label('Counter-signed by')->placeholder('—'),
                ])->columns(3),

            Section::make('Presenting Complaints')
                ->icon('heroicon-m-rectangle-list')
                ->schema([
                    TextEntry::make('presentingComplaints.name')
                        ->label('Complaints')
                        ->badge()
                        ->color('info')
                        ->placeholder('No presenting complaints recorded'),
                ]),

            Section::make('Physical Examination')
                ->icon('heroicon-m-hand-raised')
                ->schema([
                    TextEntry::make('physical_examination_general')->label('General Examination')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('physical_examination_systemic')->label('Systemic Examination')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('physical_examination_specific')->label('Clinic-Specific Examination')->placeholder('—')->columnSpanFull(),
                ]),

            Section::make('General History')
                ->icon('heroicon-m-clock')
                ->schema([
                    TextEntry::make('history_presenting_illness')->label('HPI')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('history_past_medical')->label('Past Medical')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('history_past_surgical')->label('Past Surgical')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('history_drug_allergies')->label('Drug Allergies')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('history_family')->label('Family History')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('history_social')->label('Social History')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('history_obstetric')->label('Obstetric History')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('history_developmental')->label('Developmental History')->placeholder('—')->columnSpanFull(),
                ]),

            Section::make("Doctor's Clinic Note")
                ->icon('heroicon-m-pencil-square')
                ->schema([
                    TextEntry::make('examination_findings')->label('Examination Findings')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('assessment')->label('Assessment')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('plan')->label('Plan')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('doctor_notes')->label('Doctor Notes')->placeholder('—')->columnSpanFull(),
                ]),
        ]);
    }
}
