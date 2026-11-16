<?php

namespace App\Filament\Resources\ReportSchedules\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class ReportScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ReportSchedule Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('report_template_id')->label('Report Template')->required()->relationship('report_template', 'name')->searchable()->preload(),
                            TextInput::make('name')->label('Schedule Name')->required(),
                            Select::make('frequency')->label('Frequency')->required()->options(['Daily' => 'Daily', 'Weekly' => 'Weekly', 'Monthly' => 'Monthly', 'Quarterly' => 'Quarterly', 'Annual' => 'Annual'])->searchable(),
                            TimePicker::make('run_at')->label('Run At')->required(),
                            TextInput::make('day_of_week')->label('Day of Week (0-6)')->numeric(),
                            TextInput::make('day_of_month')->label('Day of Month (1-31)')->numeric(),
                            Select::make('department_id')->label('Department')->relationship('department', 'name')->searchable()->preload(),
                            Select::make('user_id')->label('User')->relationship('user', 'name')->searchable()->preload(),
                            Toggle::make('is_active')->label('Active'),
                        ]),
                    ]),
            ]);
    }
}
