<?php

namespace App\Filament\Resources\InsuranceProviders;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\InsuranceProviders\Pages\CreateInsuranceProvider;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\InsuranceProviders\Pages\EditInsuranceProvider;
use App\Filament\Resources\InsuranceProviders\Pages\ListInsuranceProviders;
use App\Filament\Resources\InsuranceProviders\Pages\ViewInsuranceProvider;
use App\Filament\Resources\InsuranceProviders\Schemas\InsuranceProviderForm;
use App\Filament\Resources\InsuranceProviders\Schemas\InsuranceProviderInfolist;
use App\Filament\Resources\InsuranceProviders\Tables\InsuranceProvidersTable;
use App\Models\InsuranceProvider;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InsuranceProviderResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedShieldCheck;
    }

    public static function getNavigationGroup(): string
    {
        return 'Insurance';
    }
    public static function getNavigationSort(): ?int
    {
        return 1;
    }
    protected static ?string $model = InsuranceProvider::class;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return InsuranceProviderForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return InsuranceProviderInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InsuranceProvidersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListInsuranceProviders::route('/'),
            'create' => CreateInsuranceProvider::route('/create'),
            'view'   => ViewInsuranceProvider::route('/{record}'),
            'edit'   => EditInsuranceProvider::route('/{record}/edit'),
        ];
    }
}
