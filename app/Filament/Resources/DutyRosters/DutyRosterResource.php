<?php

namespace App\Filament\Resources\DutyRosters;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\DutyRosters\Pages\CreateDutyRoster;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\DutyRosters\Pages\EditDutyRoster;
use App\Filament\Resources\DutyRosters\Pages\ListDutyRosters;
use App\Filament\Resources\DutyRosters\Pages\ViewDutyRoster;
use App\Filament\Resources\DutyRosters\Schemas\DutyRosterForm;
use App\Filament\Resources\DutyRosters\Schemas\DutyRosterInfolist;
use App\Filament\Resources\DutyRosters\Tables\DutyRostersTable;
use App\Models\DutyRoster;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DutyRosterResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedCalendarDays;
    }

    public static function getNavigationGroup(): string
    {
        return 'Duty & Staff Posting';
    }
    public static function getNavigationSort(): ?int
    {
        return 3;
    }
    protected static ?string $model = DutyRoster::class;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return DutyRosterForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DutyRosterInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DutyRostersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListDutyRosters::route('/'),
            'create' => CreateDutyRoster::route('/create'),
            'view'   => ViewDutyRoster::route('/{record}'),
            'edit'   => EditDutyRoster::route('/{record}/edit'),
        ];
    }
}
