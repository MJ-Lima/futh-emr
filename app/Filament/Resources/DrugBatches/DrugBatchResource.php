<?php

namespace App\Filament\Resources\DrugBatches;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\DrugBatches\Pages\CreateDrugBatch;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\DrugBatches\Pages\EditDrugBatch;
use App\Filament\Resources\DrugBatches\Pages\ListDrugBatches;
use App\Filament\Resources\DrugBatches\Pages\ViewDrugBatch;
use App\Filament\Resources\DrugBatches\Schemas\DrugBatchForm;
use App\Filament\Resources\DrugBatches\Schemas\DrugBatchInfolist;
use App\Filament\Resources\DrugBatches\Tables\DrugBatchesTable;
use App\Models\DrugBatch;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DrugBatchResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedCubeTransparent;
    }

    public static function getNavigationGroup(): string
    {
        return 'Pharmacy';
    }
    public static function getNavigationSort(): ?int
    {
        return 3;
    }
    protected static ?string $model = DrugBatch::class;

    protected static ?string $recordTitleAttribute = 'batch_number';

    public static function form(Schema $schema): Schema
    {
        return DrugBatchForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DrugBatchInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DrugBatchesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListDrugBatches::route('/'),
            'create' => CreateDrugBatch::route('/create'),
            'view'   => ViewDrugBatch::route('/{record}'),
            'edit'   => EditDrugBatch::route('/{record}/edit'),
        ];
    }
}
