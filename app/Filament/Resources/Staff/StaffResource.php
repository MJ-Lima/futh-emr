<?php

namespace App\Filament\Resources\Staff;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\Staff\Pages\CreateStaff;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\Staff\Pages\EditStaff;
use App\Filament\Resources\Staff\Pages\ListStaff;
use App\Filament\Resources\Staff\Pages\ViewStaff;
use App\Filament\Resources\Staff\Schemas\StaffForm;
use App\Filament\Resources\Staff\Schemas\StaffInfolist;
use App\Filament\Resources\Staff\Tables\StaffTable;
use App\Models\Staff;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StaffResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedIdentification;
    }

    public static function getNavigationGroup(): string
    {
        return 'Duty & Staff Posting';
    }
    public static function getNavigationSort(): ?int
    {
        return 2;
    }
    protected static ?string $model = Staff::class;

    protected static ?string $recordTitleAttribute = 'staff_number';

    public static function form(Schema $schema): Schema
    {
        return StaffForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StaffInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StaffTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListStaff::route('/'),
            'create' => CreateStaff::route('/create'),
            'view'   => ViewStaff::route('/{record}'),
            'edit'   => EditStaff::route('/{record}/edit'),
        ];
    }
}
