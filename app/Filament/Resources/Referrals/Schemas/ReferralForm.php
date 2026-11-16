<?php

namespace App\Filament\Resources\Referrals\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use App\Models\Referral;
class ReferralForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Referral Details')
                ->icon('heroicon-m-arrow-uturn-left')
                ->description('Patient & referral routing information')
                ->schema([
                    Grid::make(3)->schema([
                        Select::make('patient_id')
                            ->label('Patient')
                            ->required()
                            ->relationship('patient', 'hospital_no')
                            ->getOptionLabelFromRecordUsing(fn ($r) => "{$r->lastname} {$r->firstname} — {$r->hospital_no}")
                            ->searchable(['firstname', 'lastname', 'hospital_no', 'phonemain'])
                            ->preload()
                            ->native(false)
                            ->columnSpan(2),
                        Select::make('patient_category')
                            ->label('Patient Category')
                            ->required()
                            ->options(Referral::CATEGORIES)
                            ->default('Outpatient')
                            ->native(false),
                    ]),
                    Grid::make(3)->schema([
                        Select::make('encounter_id')
                            ->label('Encounter (Outpatient)')
                            ->relationship('encounter', 'id')
                            ->searchable()->preload()->native(false)
                            ->helperText('For outpatient referrals'),
                        Select::make('admission_id')
                            ->label('Admission (Inpatient)')
                            ->relationship('admission', 'id')
                            ->searchable()->preload()->native(false)
                            ->helperText('For inpatient referrals'),
                        Select::make('referral_type')
                            ->label('Referral Type')
                            ->required()
                            ->options(Referral::TYPES)
                            ->default('Internal')
                            ->native(false)
                            ->live(),
                    ]),
                ]),

            Section::make('Destination')
                ->icon('heroicon-m-map-pin')
                ->schema([
                    Grid::make(3)->schema([
                        Select::make('referred_to_user_id')
                            ->label('Internal Doctor')
                            ->relationship('referredTo', 'name')
                            ->searchable()->preload()->native(false)
                            ->helperText('For internal/specialist referrals'),
                        TextInput::make('destination_facility')
                            ->label('Destination Facility')
                            ->placeholder('e.g. University of Abuja Teaching Hospital')
                            ->helperText('Required for External / Inter-Hospital referrals'),
                        TextInput::make('destination_department')
                            ->label('Destination Department')
                            ->placeholder('e.g. Cardiology, Orthopaedics'),
                        TextInput::make('destination_doctor')
                            ->label('Destination Doctor')
                            ->placeholder('Dr. (consultant name at destination)'),
                        DatePicker::make('appointment_date')->label('Appointment Date'),
                        Select::make('urgency')
                            ->label('Urgency')
                            ->required()
                            ->options(Referral::URGENCIES)
                            ->default('Routine')
                            ->native(false),
                    ]),
                ]),

            Section::make('Clinical Information')
                ->icon('heroicon-m-clipboard-document')
                ->schema([
                    Textarea::make('clinical_summary')->label('Clinical Summary')->rows(3)
                        ->placeholder('Brief history of presenting complaint'),
                    Textarea::make('diagnosis')->label('Diagnosis (working / provisional)')->rows(2),
                    Textarea::make('treatment_given')->label('Treatment Given So Far')->rows(3)
                        ->placeholder('Medications, procedures, interventions already done'),
                    Textarea::make('reason_for_referral')->label('Reason for Referral')->rows(3)
                        ->placeholder('Why the patient is being referred'),
                    Textarea::make('investigations_done')->label('Investigations Already Done')->rows(2)
                        ->placeholder('Lab results, imaging done so far'),
                    Textarea::make('investigations_requested')->label('Investigations / Action Requested')->rows(2)
                        ->placeholder('What you want the receiving team to do'),
                ]),

            Section::make('Status')
                ->icon('heroicon-m-check-badge')
                ->schema([
                    Grid::make(3)->schema([
                        DatePicker::make('referred_date')->label('Referred Date')->required()->default(now()),
                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options(Referral::STATUSES)
                            ->default('Pending')
                            ->native(false),
                        Textarea::make('receiving_feedback')->label('Receiving Facility Feedback')->rows(2)
                            ->columnSpanFull(),
                    ]),
                ]),
        ]);
    }
}
