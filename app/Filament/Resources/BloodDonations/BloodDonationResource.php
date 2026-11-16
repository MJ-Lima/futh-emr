<?php

namespace App\Filament\Resources\BloodDonations;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\BloodDonations\Pages\CreateBloodDonation;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\BloodDonations\Pages\EditBloodDonation;
use App\Filament\Resources\BloodDonations\Pages\ListBloodDonations;
use App\Filament\Resources\BloodDonations\Pages\ViewBloodDonation;
use App\Filament\Resources\BloodDonations\Schemas\BloodDonationForm;
use App\Filament\Resources\BloodDonations\Schemas\BloodDonationInfolist;
use App\Filament\Resources\BloodDonations\Tables\BloodDonationsTable;
use App\Models\BloodDonation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BloodDonationResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedBeaker;
    }

    public static function getNavigationGroup(): string
    {
        return 'Blood Bank';
    }
    public static function getNavigationSort(): ?int
    {
        return 2;
    }
    protected static ?string $model = BloodDonation::class;

    protected static ?string $recordTitleAttribute = 'donation_number';

    public static function form(Schema $schema): Schema
    {
        return BloodDonationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BloodDonationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BloodDonationsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListBloodDonations::route('/'),
            'create' => CreateBloodDonation::route('/create'),
            'view'   => ViewBloodDonation::route('/{record}'),
            'edit'   => EditBloodDonation::route('/{record}/edit'),
        ];
    }
}
