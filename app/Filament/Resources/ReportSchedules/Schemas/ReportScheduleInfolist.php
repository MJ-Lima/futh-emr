<?php

namespace App\Filament\Resources\ReportSchedules\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class ReportScheduleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ReportSchedule Information')
                    ->schema([
                        TextEntry::make('name')->label('Schedule'),
                        TextEntry::make('template.name')->label('Template'),
                        TextEntry::make('frequency')->label('Frequency'),
                        TextEntry::make('run_at')->label('Run At'),
                        TextEntry::make('last_run_at')->label('Last Run')->dateTime(),
                        TextEntry::make('next_run_at')->label('Next Run')->dateTime(),
                        TextEntry::make('is_active')->label('Active'),
                    ])
                    ->columns(2),
            ]);
    }
}
