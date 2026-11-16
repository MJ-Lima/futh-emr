<?php

namespace App\Filament\Resources\ProcedureBookings;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\ProcedureBookings\Pages\CreateProcedureBooking;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\ProcedureBookings\Pages\EditProcedureBooking;
use App\Filament\Resources\ProcedureBookings\Pages\ListProcedureBookings;
use App\Filament\Resources\ProcedureBookings\Pages\ViewProcedureBooking;
use App\Filament\Resources\ProcedureBookings\Schemas\ProcedureBookingForm;
use App\Filament\Resources\ProcedureBookings\Schemas\ProcedureBookingInfolist;
use App\Filament\Resources\ProcedureBookings\Tables\ProcedureBookingsTable;
use App\Models\ProcedureBooking;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProcedureBookingResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedCalendarDays;
    }

    public static function getNavigationGroup(): string
    {
        return 'Procedures';
    }
    public static function getNavigationSort(): ?int
    {
        return 3;
    }
    protected static ?string $model = ProcedureBooking::class;

    protected static ?string $recordTitleAttribute = 'booking_number';

    public static function form(Schema $schema): Schema
    {
        return ProcedureBookingForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProcedureBookingInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProcedureBookingsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListProcedureBookings::route('/'),
            'create' => CreateProcedureBooking::route('/create'),
            'view'   => ViewProcedureBooking::route('/{record}'),
            'edit'   => EditProcedureBooking::route('/{record}/edit'),
        ];
    }
}
