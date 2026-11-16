<?php

namespace App\Filament\Resources\InsuranceClearances;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\InsuranceClearances\Pages\CreateInsuranceClearance;
use App\Filament\Resources\InsuranceClearances\Pages\EditInsuranceClearance;
use App\Filament\Resources\InsuranceClearances\Pages\ListInsuranceClearances;
use App\Filament\Resources\InsuranceClearances\Pages\ViewInsuranceClearance;
use App\Filament\Resources\InsuranceClearances\Schemas\InsuranceClearanceForm;
use App\Filament\Resources\InsuranceClearances\Schemas\InsuranceClearanceInfolist;
use App\Filament\Resources\InsuranceClearances\Tables\InsuranceClearancesTable;
use App\Models\InsuranceClearance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InsuranceClearanceResource extends Resource
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
    public static function getNavigationLabel(): string
    {
        return 'Insurance Clearances';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }
    protected static ?string $model = InsuranceClearance::class;

    protected static ?string $recordTitleAttribute = 'clearance_number';

    public static function form(Schema $schema): Schema { return InsuranceClearanceForm::configure($schema); }
    public static function infolist(Schema $schema): Schema { return InsuranceClearanceInfolist::configure($schema); }
    public static function table(Table $table): Table { return InsuranceClearancesTable::configure($table); }

    public static function getPages(): array {
        return [
            'index' => ListInsuranceClearances::route('/'),
            'create' => CreateInsuranceClearance::route('/create'),
            'view' => ViewInsuranceClearance::route('/{record}'),
            'edit' => EditInsuranceClearance::route('/{record}/edit'),
        ];
    }
}
