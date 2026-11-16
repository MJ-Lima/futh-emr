<?php

namespace App\Filament\Resources\PatientInsurance\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class PatientInsuranceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('PatientInsurance Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('patient_id')->label('Patient')->required()->relationship('patient', 'hospital_no')->getOptionLabelUsing(fn ($record) => $record?->full_name ?? 'Unknown')->searchable()->preload(),
                            Select::make('insurance_provider_id')->label('Provider')->required()->relationship('provider', 'name')->searchable()->preload(),
                            Select::make('insurance_plan_id')->label('Plan')->required()->relationship('plan', 'name')->searchable()->preload(),
                            TextInput::make('policy_number')->label('Policy Number')->required(),
                            TextInput::make('member_id')->label('Member ID'),
                            TextInput::make('enrollee_code')->label('Enrollee Code'),
                            Select::make('principal_relationship')->label('Relationship to Principal')->required()->options(['Self' => 'Self', 'Spouse' => 'Spouse', 'Child' => 'Child', 'Parent' => 'Parent', 'Sibling' => 'Sibling', 'Dependant' => 'Dependant'])->searchable(),
                            TextInput::make('principal_name')->label('Principal Name'),
                            TextInput::make('principal_dob')->label('Principal DOB'),
                            TextInput::make('principal_phone')->label('Principal Phone')->tel(),
                            TextInput::make('principal_nin')->label('Principal NIN'),
                            DatePicker::make('enrollment_date')->label('Enrollment Date')->required(),
                            DatePicker::make('effective_date')->label('Effective Date')->required(),
                            DatePicker::make('expiry_date')->label('Expiry Date')->required(),
                            TextInput::make('remaining_benefit')->label('Remaining Benefit')->numeric(),
                            TextInput::make('utilized_benefit')->label('Utilized Benefit')->numeric(),
                            Toggle::make('is_primary')->label('Is Primary'),
                            Select::make('verification_status')->label('Verification')->required()->options(['Pending' => 'Pending', 'Verified' => 'Verified', 'Rejected' => 'Rejected', 'Expired' => 'Expired'])->searchable(),
                            Select::make('status')->label('Status')->required()->options(['Active' => 'Active', 'Suspended' => 'Suspended', 'Expired' => 'Expired', 'Cancelled' => 'Cancelled'])->searchable(),
                            Textarea::make('notes')->label('Notes'),
                        ]),
                    ]),
            ]);
    }
}
