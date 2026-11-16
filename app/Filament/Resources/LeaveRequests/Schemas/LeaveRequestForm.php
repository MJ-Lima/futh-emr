<?php

namespace App\Filament\Resources\LeaveRequests\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('LeaveRequest Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('user_id')->label('Staff')->required()->relationship('user', 'name')->searchable()->preload(),
                            Select::make('staff_id')->label('Staff Record')->relationship('staff', 'staff_number')->searchable()->preload(),
                            Select::make('leave_type')->label('Leave Type')->required()->options(['Annual' => 'Annual', 'Sick' => 'Sick', 'Casual' => 'Casual', 'Maternity' => 'Maternity', 'Paternity' => 'Paternity', 'Compassionate' => 'Compassionate', 'Study' => 'Study', 'Sabbatical' => 'Sabbatical', 'Unpaid' => 'Unpaid', 'Other' => 'Other'])->searchable(),
                            DatePicker::make('start_date')->label('Start Date')->required(),
                            DatePicker::make('end_date')->label('End Date')->required(),
                            TextInput::make('days_requested')->label('Days Requested')->numeric(),
                            Textarea::make('reason')->label('Reason')->required(),
                            TextInput::make('handover_to')->label('Handover To'),
                            Textarea::make('handover_notes')->label('Handover Notes'),
                            Select::make('status')->label('Status')->required()->options(['Draft' => 'Draft', 'Pending' => 'Pending', 'Recommended' => 'Recommended', 'Approved' => 'Approved', 'Rejected' => 'Rejected', 'Cancelled' => 'Cancelled', 'On Leave' => 'On Leave', 'Returned' => 'Returned'])->searchable(),
                            Textarea::make('review_notes')->label('Review Notes'),
                            Textarea::make('rejection_reason')->label('Rejection Reason'),
                        ]),
                    ]),
            ]);
    }
}
