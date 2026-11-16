<?php

namespace App\Filament\Resources\Hospitals;

use App\Filament\Traits\HasRoleBasedAccess;
use App\Filament\Resources\Hospitals\Pages\CreateHospital;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\Hospitals\Pages\EditHospital;
use App\Filament\Resources\Hospitals\Pages\ListHospitals;
use App\Filament\Resources\Hospitals\Pages\ViewHospital;
use App\Filament\Resources\Hospitals\Schemas\HospitalForm;
use App\Filament\Resources\Hospitals\Schemas\HospitalInfolist;
use App\Filament\Resources\Hospitals\Tables\HospitalsTable;
use App\Models\Hospital;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HospitalResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedRectangleStack;
    }
    protected static ?string $model = Hospital::class;

    public static function form(Schema $schema): Schema
    {
        return HospitalForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return HospitalInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HospitalsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHospitals::route('/'),
            'create' => CreateHospital::route('/create'),
            'view' => ViewHospital::route('/{record}'),
            'edit' => EditHospital::route('/{record}/edit'),
        ];
    }
}
