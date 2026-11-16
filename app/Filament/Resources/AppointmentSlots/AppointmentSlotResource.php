<?php

namespace App\Filament\Resources\AppointmentSlots;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\AppointmentSlots\Pages\CreateAppointmentSlot;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\AppointmentSlots\Pages\EditAppointmentSlot;
use App\Filament\Resources\AppointmentSlots\Pages\ListAppointmentSlots;
use App\Filament\Resources\AppointmentSlots\Pages\ViewAppointmentSlot;
use App\Filament\Resources\AppointmentSlots\Schemas\AppointmentSlotForm;
use App\Filament\Resources\AppointmentSlots\Schemas\AppointmentSlotInfolist;
use App\Filament\Resources\AppointmentSlots\Tables\AppointmentSlotsTable;
use App\Models\AppointmentSlot;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AppointmentSlotResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedRectangleStack;
    }

    public static function getNavigationGroup(): string
    {
        return 'Schedule Configuration';
    }
    public static function getNavigationSort(): ?int
    {
        return 4;
    }
    protected static ?string $model = AppointmentSlot::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return AppointmentSlotForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AppointmentSlotInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AppointmentSlotsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListAppointmentSlots::route('/'),
            'create' => CreateAppointmentSlot::route('/create'),
            'view'   => ViewAppointmentSlot::route('/{record}'),
            'edit'   => EditAppointmentSlot::route('/{record}/edit'),
        ];
    }
}
