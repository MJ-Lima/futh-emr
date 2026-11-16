<?php

namespace App\Filament\Resources\SavedReports;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\SavedReports\Pages\CreateSavedReport;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\SavedReports\Pages\EditSavedReport;
use App\Filament\Resources\SavedReports\Pages\ListSavedReports;
use App\Filament\Resources\SavedReports\Pages\ViewSavedReport;
use App\Filament\Resources\SavedReports\Schemas\SavedReportForm;
use App\Filament\Resources\SavedReports\Schemas\SavedReportInfolist;
use App\Filament\Resources\SavedReports\Tables\SavedReportsTable;
use App\Models\SavedReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SavedReportResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedDocumentText;
    }

    public static function getNavigationGroup(): string
    {
        return 'Reports';
    }
    public static function getNavigationSort(): ?int
    {
        return 2;
    }
    protected static ?string $model = SavedReport::class;

    protected static ?string $recordTitleAttribute = 'report_number';

    public static function form(Schema $schema): Schema
    {
        return SavedReportForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SavedReportInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SavedReportsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListSavedReports::route('/'),
            'create' => CreateSavedReport::route('/create'),
            'view'   => ViewSavedReport::route('/{record}'),
            'edit'   => EditSavedReport::route('/{record}/edit'),
        ];
    }
}
