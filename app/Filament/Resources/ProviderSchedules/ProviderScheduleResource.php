<?php

namespace App\Filament\Resources\ProviderSchedules;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\ProviderSchedules\Pages\CreateProviderSchedule;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\ProviderSchedules\Pages\EditProviderSchedule;
use App\Filament\Resources\ProviderSchedules\Pages\ListProviderSchedules;
use App\Filament\Resources\ProviderSchedules\Pages\ViewProviderSchedule;
use App\Filament\Resources\ProviderSchedules\Schemas\ProviderScheduleForm;
use App\Filament\Resources\ProviderSchedules\Schemas\ProviderScheduleInfolist;
use App\Filament\Resources\ProviderSchedules\Tables\ProviderSchedulesTable;
use App\Models\ProviderSchedule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProviderScheduleResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedUserGroup;
    }

    public static function getNavigationGroup(): string
    {
        return 'Schedule Configuration';
    }
    public static function getNavigationSort(): ?int
    {
        return 3;
    }
    protected static ?string $model = ProviderSchedule::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ProviderScheduleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProviderScheduleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProviderSchedulesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListProviderSchedules::route('/'),
            'create' => CreateProviderSchedule::route('/create'),
            'view'   => ViewProviderSchedule::route('/{record}'),
            'edit'   => EditProviderSchedule::route('/{record}/edit'),
        ];
    }
}
