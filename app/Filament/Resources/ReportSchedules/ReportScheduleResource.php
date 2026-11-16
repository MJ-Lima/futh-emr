<?php

namespace App\Filament\Resources\ReportSchedules;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\ReportSchedules\Pages\CreateReportSchedule;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\ReportSchedules\Pages\EditReportSchedule;
use App\Filament\Resources\ReportSchedules\Pages\ListReportSchedules;
use App\Filament\Resources\ReportSchedules\Pages\ViewReportSchedule;
use App\Filament\Resources\ReportSchedules\Schemas\ReportScheduleForm;
use App\Filament\Resources\ReportSchedules\Schemas\ReportScheduleInfolist;
use App\Filament\Resources\ReportSchedules\Tables\ReportSchedulesTable;
use App\Models\ReportSchedule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReportScheduleResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedCalendarDays;
    }

    public static function getNavigationGroup(): string
    {
        return 'Reports';
    }
    public static function getNavigationSort(): ?int
    {
        return 3;
    }
    protected static ?string $model = ReportSchedule::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ReportScheduleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ReportScheduleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReportSchedulesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListReportSchedules::route('/'),
            'create' => CreateReportSchedule::route('/create'),
            'view'   => ViewReportSchedule::route('/{record}'),
            'edit'   => EditReportSchedule::route('/{record}/edit'),
        ];
    }
}
