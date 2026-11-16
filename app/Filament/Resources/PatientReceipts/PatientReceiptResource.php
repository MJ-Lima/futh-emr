<?php

namespace App\Filament\Resources\PatientReceipts;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\PatientReceipts\Pages\CreatePatientReceipt;
use App\Filament\Resources\PatientReceipts\Pages\EditPatientReceipt;
use App\Filament\Resources\PatientReceipts\Pages\ListPatientReceipts;
use App\Filament\Resources\PatientReceipts\Pages\ViewPatientReceipt;
use App\Filament\Resources\PatientReceipts\Schemas\PatientReceiptForm;
use App\Filament\Resources\PatientReceipts\Schemas\PatientReceiptInfolist;
use App\Filament\Resources\PatientReceipts\Tables\PatientReceiptsTable;
use App\Models\PatientReceipt;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PatientReceiptResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedReceiptPercent;
    }

    public static function getNavigationGroup(): string
    {
        return 'Accounts';
    }
    public static function getNavigationLabel(): string
    {
        return 'Patient Receipts';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }
    protected static ?string $model = PatientReceipt::class;

    protected static ?string $recordTitleAttribute = 'receipt_number';

    public static function form(Schema $schema): Schema { return PatientReceiptForm::configure($schema); }
    public static function infolist(Schema $schema): Schema { return PatientReceiptInfolist::configure($schema); }
    public static function table(Table $table): Table { return PatientReceiptsTable::configure($table); }

    public static function getPages(): array {
        return [
            'index' => ListPatientReceipts::route('/'),
            'create' => CreatePatientReceipt::route('/create'),
            'view' => ViewPatientReceipt::route('/{record}'),
            'edit' => EditPatientReceipt::route('/{record}/edit'),
        ];
    }
}
