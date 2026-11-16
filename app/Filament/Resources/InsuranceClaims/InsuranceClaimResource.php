<?php

namespace App\Filament\Resources\InsuranceClaims;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\InsuranceClaims\Pages\CreateInsuranceClaim;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\InsuranceClaims\Pages\EditInsuranceClaim;
use App\Filament\Resources\InsuranceClaims\Pages\ListInsuranceClaims;
use App\Filament\Resources\InsuranceClaims\Pages\ViewInsuranceClaim;
use App\Filament\Resources\InsuranceClaims\Schemas\InsuranceClaimForm;
use App\Filament\Resources\InsuranceClaims\Schemas\InsuranceClaimInfolist;
use App\Filament\Resources\InsuranceClaims\Tables\InsuranceClaimsTable;
use App\Models\InsuranceClaim;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InsuranceClaimResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedDocumentText;
    }

    public static function getNavigationGroup(): string
    {
        return 'Insurance';
    }
    public static function getNavigationSort(): ?int
    {
        return 4;
    }
    protected static ?string $model = InsuranceClaim::class;

    protected static ?string $recordTitleAttribute = 'claim_number';

    public static function form(Schema $schema): Schema
    {
        return InsuranceClaimForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return InsuranceClaimInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InsuranceClaimsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListInsuranceClaims::route('/'),
            'create' => CreateInsuranceClaim::route('/create'),
            'view'   => ViewInsuranceClaim::route('/{record}'),
            'edit'   => EditInsuranceClaim::route('/{record}/edit'),
        ];
    }
}
