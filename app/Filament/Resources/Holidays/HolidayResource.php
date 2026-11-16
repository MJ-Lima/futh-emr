<?php

namespace App\Filament\Resources\Holidays;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\Holidays\Pages\CreateHoliday;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\Holidays\Pages\EditHoliday;
use App\Filament\Resources\Holidays\Pages\ListHolidays;
use App\Filament\Resources\Holidays\Pages\ViewHoliday;
use App\Filament\Resources\Holidays\Schemas\HolidayForm;
use App\Filament\Resources\Holidays\Schemas\HolidayInfolist;
use App\Filament\Resources\Holidays\Tables\HolidaysTable;
use App\Models\Holiday;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HolidayResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedGift;
    }

    public static function getNavigationGroup(): string
    {
        return 'Schedule Configuration';
    }
    public static function getNavigationSort(): ?int
    {
        return 5;
    }
    protected static ?string $model = Holiday::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return HolidayForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return HolidayInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HolidaysTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListHolidays::route('/'),
            'create' => CreateHoliday::route('/create'),
            'view'   => ViewHoliday::route('/{record}'),
            'edit'   => EditHoliday::route('/{record}/edit'),
        ];
    }
}
