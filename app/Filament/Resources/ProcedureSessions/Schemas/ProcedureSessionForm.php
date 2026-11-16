<?php

namespace App\Filament\Resources\ProcedureSessions\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class ProcedureSessionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ProcedureSession Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('procedure_booking_id')->label('Booking')->required()->relationship('procedure_booking', 'booking_number')->searchable()->preload(),
                            Select::make('patient_id')->label('Patient')->required()->relationship('patient', 'hospital_no')->getOptionLabelUsing(fn ($record) => $record?->full_name ?? 'Unknown')->searchable()->preload(),
                            Select::make('procedure_id')->label('Procedure')->required()->relationship('procedure', 'name')->searchable()->preload(),
                            Select::make('surgeon_id')->label('Surgeon')->relationship('surgeon', 'name')->searchable()->preload(),
                            Select::make('anaesthetist_id')->label('Anaesthetist')->relationship('anaesthetist', 'name')->searchable()->preload(),
                            Select::make('theatre_id')->label('Theatre')->relationship('theatre', 'name')->searchable()->preload(),
                            DateTimePicker::make('actual_start')->label('Actual Start')->required(),
                            DateTimePicker::make('actual_end')->label('Actual End'),
                            Select::make('anaesthesia_used')->label('Anaesthesia Used')->required()->options(['Local' => 'Local', 'Regional' => 'Regional', 'Spinal' => 'Spinal', 'Epidural' => 'Epidural', 'General' => 'General', 'Sedation' => 'Sedation', 'None' => 'None'])->searchable(),
                            Textarea::make('anaesthesia_notes')->label('Anaesthesia Notes'),
                            Textarea::make('findings_intra_op')->label('Findings (Intra-Op)'),
                            Textarea::make('procedure_performed')->label('Procedure Performed'),
                            Textarea::make('steps_taken')->label('Steps Taken'),
                            Textarea::make('complications_intra_op')->label('Complications (Intra-Op)'),
                            TextInput::make('blood_loss_ml')->label('Blood Loss (ml)'),
                            Textarea::make('specimens_sent')->label('Specimens Sent'),
                            Textarea::make('implants_used')->label('Implants Used'),
                            Textarea::make('drains_placed')->label('Drains Placed'),
                            Textarea::make('dressing_applied')->label('Dressing Applied'),
                            Textarea::make('post_op_instructions')->label('Post-Op Instructions'),
                            Select::make('outcome')->label('Outcome')->required()->options(['Successful' => 'Successful', 'Complicated' => 'Complicated', 'Incomplete' => 'Incomplete', 'Abandoned' => 'Abandoned', 'Death' => 'Death'])->searchable(),
                            Textarea::make('notes')->label('Notes'),
                            Select::make('status')->label('Status')->required()->options(['In Progress' => 'In Progress', 'Completed' => 'Completed', 'Abandoned' => 'Abandoned', 'Cancelled' => 'Cancelled'])->searchable(),
                        ]),
                    ]),
            ]);
    }
}
