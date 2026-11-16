<?php

namespace App\Filament\Resources\WardRoundNotes\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use App\Models\WardRoundNote;
class WardRoundNoteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Patient & Round Info')
                ->icon('heroicon-m-link')
                ->schema([
                    TextEntry::make('note_number')->label('Note #')->badge()->color('info')->copyable(),
                    TextEntry::make('round_date')->label('Round Date')->date('d M Y'),
                    TextEntry::make('round_time')->label('Time')->time('H:i'),
                    TextEntry::make('round_type')->label('Round Type')->badge(),
                    TextEntry::make('patient.full_name')->label('Patient'),
                    TextEntry::make('patient.hospital_no')->label('Hospital No'),
                    TextEntry::make('admission.admission_number')->label('Admission #')->placeholder('—')->badge(),
                    TextEntry::make('ward.name')->label('Ward')->placeholder('—'),
                    TextEntry::make('doctor.name')->label('Doctor'),
                ])->columns(3),

            Section::make('Vital Signs')
                ->icon('heroicon-m-heart')
                ->schema([
                    TextEntry::make('temperature')->label('Temp (°C)')->placeholder('—'),
                    TextEntry::make('systolic_bp')->label('Systolic')->placeholder('—')
                        ->formatStateUsing(fn ($s, $record) => $s ? "{$s}/{$record->diastolic_bp}" : '—'),
                    TextEntry::make('heart_rate')->label('Pulse')->placeholder('—'),
                    TextEntry::make('respiratory_rate')->label('Resp Rate')->placeholder('—'),
                    TextEntry::make('spo2')->label('SpO₂ %')->placeholder('—'),
                    TextEntry::make('blood_glucose')->label('Glucose')->placeholder('—'),
                    TextEntry::make('weight_kg')->label('Weight (kg)')->placeholder('—'),
                ])->columns(4),

            Section::make('SOAP Note')
                ->icon('heroicon-m-clipboard-document')
                ->schema([
                    TextEntry::make('subjective')->label('S — Subjective')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('objective')->label('O — Objective')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('assessment')->label('A — Assessment')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('plan')->label('P — Plan')->placeholder('—')->columnSpanFull(),
                ]),

            Section::make('Plan & Instructions')
                ->icon('heroicon-m-check-circle')
                ->schema([
                    TextEntry::make('investigations_ordered')->label('Investigations Ordered')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('medications_changes')->label('Medications Changes')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('plan_for_discharge')->label('Plan for Discharge')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('nurse_action_required')->label('Nurse Action Required')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('doctor_notes')->label('Doctor\'s Notes')->placeholder('—')->columnSpanFull(),
                ]),
        ]);
    }
}
