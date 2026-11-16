<?php

namespace App\Filament\Resources\Referrals;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\Referrals\Pages\CreateReferral;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\Referrals\Pages\EditReferral;
use App\Filament\Resources\Referrals\Pages\ListReferrals;
use App\Filament\Resources\Referrals\Pages\ViewReferral;
use App\Filament\Resources\Referrals\Schemas\ReferralForm;
use App\Filament\Resources\Referrals\Schemas\ReferralInfolist;
use App\Filament\Resources\Referrals\Tables\ReferralsTable;
use App\Models\Referral;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReferralResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedArrowUturnLeft;
    }

    public static function getNavigationGroup(): string
    {
        return 'Clinical';
    }
    public static function getNavigationLabel(): string
    {
        return 'Referrals';
    }

    public static function getNavigationSort(): ?int
    {
        return 13;
    }
    protected static ?string $model = Referral::class;

    protected static ?string $recordTitleAttribute = 'referral_number';

    public static function form(Schema $schema): Schema
    {
        return ReferralForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ReferralInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReferralsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListReferrals::route('/'),
            'create' => CreateReferral::route('/create'),
            'view'   => ViewReferral::route('/{record}'),
            'edit'   => EditReferral::route('/{record}/edit'),
        ];
    }
}
