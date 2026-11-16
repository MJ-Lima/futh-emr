<?php

namespace App\Filament\Resources\LeaveRequests\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class LeaveRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('LeaveRequest Information')
                    ->schema([
                        TextEntry::make('leave_number')->label('Leave #'),
                        TextEntry::make('user.name')->label('Staff'),
                        TextEntry::make('leave_type')->label('Type'),
                        TextEntry::make('start_date')->label('From')->date(),
                        TextEntry::make('end_date')->label('To')->date(),
                        TextEntry::make('days_requested')->label('Days'),
                        TextEntry::make('status')->label('Status'),
                    ])
                    ->columns(2),
            ]);
    }
}
