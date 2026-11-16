<?php

namespace App\Filament\Resources\BloodDonors;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\BloodDonors\Pages\CreateBloodDonor;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\BloodDonors\Pages\EditBloodDonor;
use App\Filament\Resources\BloodDonors\Pages\ListBloodDonors;
use App\Filament\Resources\BloodDonors\Pages\ViewBloodDonor;
use App\Filament\Resources\BloodDonors\Schemas\BloodDonorForm;
use App\Filament\Resources\BloodDonors\Schemas\BloodDonorInfolist;
use App\Filament\Resources\BloodDonors\Tables\BloodDonorsTable;
use App\Models\BloodDonor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BloodDonorResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedUserGroup;
    }

    public static function getNavigationGroup(): string
    {
        return 'Blood Bank';
    }
    public static function getNavigationSort(): ?int
    {
        return 1;
    }
    protected static ?string $model = BloodDonor::class;

    protected static ?string $recordTitleAttribute = 'donor_number';

    public static function form(Schema $schema): Schema
    {
        return BloodDonorForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BloodDonorInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BloodDonorsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListBloodDonors::route('/'),
            'create' => CreateBloodDonor::route('/create'),
            'view'   => ViewBloodDonor::route('/{record}'),
            'edit'   => EditBloodDonor::route('/{record}/edit'),
        ];
    }
}
