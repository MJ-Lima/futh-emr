<?php

namespace App\Filament\Resources\ResidentAdmissionNotes\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class ResidentAdmissionNoteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Note Details')
                ->icon('heroicon-m-clipboard-document-check')
                ->schema([
                    TextEntry::make('note_number')->label('Note #')->badge()->color('info')->copyable(),
                    TextEntry::make('patient.full_name')->label('Patient'),
                    TextEntry::make('patient.hospital_no')->label('Hospital No'),
                    TextEntry::make('admission.admission_number')->label('Admission #')->placeholder('—')->badge(),
                    TextEntry::make('encounter.encounter_number')->label('Encounter #')->placeholder('—')->badge(),
                    TextEntry::make('residentDoctor.name')->label('Resident Doctor'),
                    TextEntry::make('supervisingConsultant.name')->label('Supervising Consultant')->placeholder('—'),
                    TextEntry::make('status')->label('Status')->badge()
                        ->color(fn ($s) => \App\Models\ResidentAdmissionNote::STATUS_COLORS[$s] ?? 'gray'),
                    TextEntry::make('submitted_at')->label('Submitted At')->dateTime('d M Y, H:i')->placeholder('—'),
                    TextEntry::make('countersigned_at')->label('Counter-signed At')->dateTime('d M Y, H:i')->placeholder('—'),
                ])->columns(3),

            Section::make('Presenting Complaint & HPI')
                ->icon('heroicon-m-rectangle-list')
                ->schema([
                    TextEntry::make('presenting_complaint')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('history_presenting_illness')->label('HPI')->placeholder('—')->columnSpanFull(),
                ]),

            Section::make('History')
                ->icon('heroicon-m-clock')
                ->schema([
                    TextEntry::make('past_medical_history')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('drug_history')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('family_history')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('social_history')->placeholder('—')->columnSpanFull(),
                ]),

            Section::make('Examination')
                ->icon('heroicon-m-hand-raised')
                ->schema([
                    TextEntry::make('physical_examination')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('systemic_review')->placeholder('—')->columnSpanFull(),
                ]),

            Section::make('Diagnosis & Plan')
                ->icon('heroicon-m-book-open')
                ->schema([
                    TextEntry::make('provisional_diagnosis')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('differential_diagnosis')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('investigations_ordered')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('management_plan')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('resident_notes')->label('Resident\'s Notes')->placeholder('—')->columnSpanFull(),
                ]),

            Section::make('Consultant Feedback')
                ->icon('heroicon-m-chat-bubble-left-right')
                ->schema([
                    TextEntry::make('consultant_feedback')->placeholder('—')->columnSpanFull(),
                ]),
        ]);
    }
}
