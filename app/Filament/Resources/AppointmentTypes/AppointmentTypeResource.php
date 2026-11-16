<?php

namespace App\Filament\Resources\AppointmentTypes;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\AppointmentTypes\Pages\CreateAppointmentType;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\AppointmentTypes\Pages\EditAppointmentType;
use App\Filament\Resources\AppointmentTypes\Pages\ListAppointmentTypes;
use App\Filament\Resources\AppointmentTypes\Pages\ViewAppointmentType;
use App\Filament\Resources\AppointmentTypes\Schemas\AppointmentTypeForm;
use App\Filament\Resources\AppointmentTypes\Schemas\AppointmentTypeInfolist;
use App\Filament\Resources\AppointmentTypes\Tables\AppointmentTypesTable;
use App\Models\AppointmentType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AppointmentTypeResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedSwatch;
    }

    public static function getNavigationGroup(): string
    {
        return 'Schedule Configuration';
    }
    public static function getNavigationSort(): ?int
    {
        return 1;
    }
    protected static ?string $model = AppointmentType::class;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return AppointmentTypeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AppointmentTypeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AppointmentTypesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListAppointmentTypes::route('/'),
            'create' => CreateAppointmentType::route('/create'),
            'view'   => ViewAppointmentType::route('/{record}'),
            'edit'   => EditAppointmentType::route('/{record}/edit'),
        ];
    }
}
