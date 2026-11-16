<?php

namespace App\Filament\Resources\LabTests;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\LabTests\Pages\CreateLabTest;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\LabTests\Pages\EditLabTest;
use App\Filament\Resources\LabTests\Pages\ListLabTests;
use App\Filament\Resources\LabTests\Pages\ViewLabTest;
use App\Filament\Resources\LabTests\Schemas\LabTestForm;
use App\Filament\Resources\LabTests\Schemas\LabTestInfolist;
use App\Filament\Resources\LabTests\Tables\LabTestsTable;
use App\Models\LabTest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LabTestResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedClipboardDocumentList;
    }

    public static function getNavigationGroup(): string
    {
        return 'Laboratory';
    }
    public static function getNavigationSort(): ?int
    {
        return 2;
    }
    protected static ?string $model = LabTest::class;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return LabTestForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LabTestInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LabTestsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListLabTests::route('/'),
            'create' => CreateLabTest::route('/create'),
            'view'   => ViewLabTest::route('/{record}'),
            'edit'   => EditLabTest::route('/{record}/edit'),
        ];
    }
}
