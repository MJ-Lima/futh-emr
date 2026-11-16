<?php

namespace App\Filament\Resources\ChartOfAccounts;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\ChartOfAccounts\Pages\CreateChartOfAccount;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\ChartOfAccounts\Pages\EditChartOfAccount;
use App\Filament\Resources\ChartOfAccounts\Pages\ListChartOfAccounts;
use App\Filament\Resources\ChartOfAccounts\Pages\ViewChartOfAccount;
use App\Filament\Resources\ChartOfAccounts\Schemas\ChartOfAccountForm;
use App\Filament\Resources\ChartOfAccounts\Schemas\ChartOfAccountInfolist;
use App\Filament\Resources\ChartOfAccounts\Tables\ChartOfAccountsTable;
use App\Models\ChartOfAccount;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ChartOfAccountResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedChartBar;
    }

    public static function getNavigationGroup(): string
    {
        return 'Accounting';
    }
    public static function getNavigationSort(): ?int
    {
        return 1;
    }
    protected static ?string $model = ChartOfAccount::class;

    protected static ?string $recordTitleAttribute = 'account_code';

    public static function form(Schema $schema): Schema
    {
        return ChartOfAccountForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ChartOfAccountInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ChartOfAccountsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListChartOfAccounts::route('/'),
            'create' => CreateChartOfAccount::route('/create'),
            'view'   => ViewChartOfAccount::route('/{record}'),
            'edit'   => EditChartOfAccount::route('/{record}/edit'),
        ];
    }
}
