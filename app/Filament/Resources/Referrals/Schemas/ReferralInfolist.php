<?php

namespace App\Filament\Resources\Referrals\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use App\Models\Referral;
class ReferralInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Referral Details')
                ->icon('heroicon-m-arrow-uturn-left')
                ->schema([
                    TextEntry::make('referral_number')->label('Referral #')->badge()->color('info')->copyable(),
                    TextEntry::make('referral_type')->label('Type')->badge(),
                    TextEntry::make('patient_category')->label('Category')->badge()
                        ->color(fn ($s) => match ($s) {
                            'Inpatient' => 'warning',
                            'Emergency'  => 'danger',
                            default      => 'success',
                        }),
                    TextEntry::make('urgency')->label('Urgency')->badge()
                        ->color(fn ($s) => Referral::URGENCY_COLORS[$s] ?? 'gray'),
                    TextEntry::make('status')->label('Status')->badge()
                        ->color(fn ($s) => Referral::STATUS_COLORS[$s] ?? 'gray'),
                    TextEntry::make('referred_date')->label('Referred')->date('d M Y'),
                    TextEntry::make('appointment_date')->label('Appointment')->date('d M Y')->placeholder('—'),
                ])->columns(3),

            Section::make('Patient & Referrer')
                ->icon('heroicon-m-user')
                ->schema([
                    TextEntry::make('patient.full_name')->label('Patient'),
                    TextEntry::make('patient.hospital_no')->label('Hospital No'),
                    TextEntry::make('patient.age')->label('Age')->placeholder('—'),
                    TextEntry::make('patient.gender')->label('Gender')->formatStateUsing(fn ($s) => ucfirst($s ?? '')),
                    TextEntry::make('referrer.name')->label('Referred By'),
                    TextEntry::make('referredTo.name')->label('Internal Doctor')->placeholder('—'),
                    TextEntry::make('encounter_id')->label('Encounter #')->placeholder('—'),
                    TextEntry::make('admission_id')->label('Admission #')->placeholder('—'),
                ])->columns(3),

            Section::make('Destination')
                ->icon('heroicon-m-map-pin')
                ->schema([
                    TextEntry::make('destination_facility')->label('Facility')->placeholder('—'),
                    TextEntry::make('destination_department')->label('Department')->placeholder('—'),
                    TextEntry::make('destination_doctor')->label('Doctor')->placeholder('—'),
                ])->columns(3),

            Section::make('Clinical Information')
                ->icon('heroicon-m-clipboard-document')
                ->schema([
                    TextEntry::make('clinical_summary')->label('Clinical Summary')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('diagnosis')->label('Diagnosis')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('treatment_given')->label('Treatment Given')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('reason_for_referral')->label('Reason for Referral')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('investigations_done')->label('Investigations Done')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('investigations_requested')->label('Investigations Requested')->placeholder('—')->columnSpanFull(),
                ]),

            Section::make('Receiving Feedback')
                ->icon('heroicon-m-chat-bubble-left-right')
                ->schema([
                    TextEntry::make('received_at')->label('Received At')->dateTime('d M Y, H:i')->placeholder('—'),
                    TextEntry::make('receiver.name')->label('Received By')->placeholder('—'),
                    TextEntry::make('receiving_feedback')->label('Feedback')->placeholder('—')->columnSpanFull(),
                ])->columns(2),
        ]);
    }
}
