<?php

namespace App\Filament\Resources\ProcedureBookings\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class ProcedureBookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ProcedureBooking Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('patient_id')->label('Patient')->required()->relationship('patient', 'hospital_no')->getOptionLabelUsing(fn ($record) => $record?->full_name ?? 'Unknown')->searchable()->preload(),
                            Select::make('encounter_id')->label('Encounter')->relationship('encounter', 'encounter_number')->searchable()->preload(),
                            Select::make('admission_id')->label('Admission')->relationship('admission', 'admission_number')->searchable()->preload(),
                            Select::make('procedure_id')->label('Procedure')->required()->relationship('procedure', 'name')->searchable()->preload(),
                            Select::make('surgeon_id')->label('Surgeon')->relationship('surgeon', 'name')->searchable()->preload(),
                            Select::make('anaesthetist_id')->label('Anaesthetist')->relationship('anaesthetist', 'name')->searchable()->preload(),
                            Select::make('assistant_id')->label('Assistant')->relationship('assistant', 'name')->searchable()->preload(),
                            Select::make('scrub_nurse_id')->label('Scrub Nurse')->relationship('scrub_nurse', 'name')->searchable()->preload(),
                            Select::make('circulating_nurse_id')->label('Circulating Nurse')->relationship('circulating_nurse', 'name')->searchable()->preload(),
                            Select::make('theatre_id')->label('Theatre')->relationship('theatre', 'name')->searchable()->preload(),
                            DateTimePicker::make('scheduled_start')->label('Scheduled Start')->required(),
                            DateTimePicker::make('scheduled_end')->label('Scheduled End'),
                            Select::make('priority')->label('Priority')->required()->options(['Elective' => 'Elective', 'Urgent' => 'Urgent', 'Emergency' => 'Emergency'])->searchable(),
                            Select::make('booking_status')->label('Status')->required()->options(['Scheduled' => 'Scheduled', 'Confirmed' => 'Confirmed', 'In Progress' => 'In Progress', 'Completed' => 'Completed', 'Cancelled' => 'Cancelled', 'Postponed' => 'Postponed', 'No Show' => 'No Show'])->searchable(),
                            DatePicker::make('pre_op_assessment_date')->label('Pre-Op Assessment Date'),
                            Toggle::make('consent_signed')->label('Consent Signed'),
                            Toggle::make('fasting_confirmed')->label('Fasting Confirmed'),
                            Toggle::make('investigations_done')->label('Investigations Done'),
                            Toggle::make('anaesthesia_review_done')->label('Anaesthesia Review Done'),
                            Toggle::make('blood_reserved')->label('Blood Reserved'),
                            Textarea::make('pre_op_notes')->label('Pre-Op Notes'),
                            Textarea::make('cancellation_reason')->label('Cancellation Reason'),
                            Textarea::make('notes')->label('Notes'),
                        ]),
                    ]),
            ]);
    }
}
