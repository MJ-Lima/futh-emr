<?php

namespace App\Filament\Resources\ReportTemplates;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\ReportTemplates\Pages\CreateReportTemplate;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\ReportTemplates\Pages\EditReportTemplate;
use App\Filament\Resources\ReportTemplates\Pages\ListReportTemplates;
use App\Filament\Resources\ReportTemplates\Pages\ViewReportTemplate;
use App\Filament\Resources\ReportTemplates\Schemas\ReportTemplateForm;
use App\Filament\Resources\ReportTemplates\Schemas\ReportTemplateInfolist;
use App\Filament\Resources\ReportTemplates\Tables\ReportTemplatesTable;
use App\Models\ReportTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReportTemplateResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedDocumentChartBar;
    }

    public static function getNavigationGroup(): string
    {
        return 'Reports';
    }
    public static function getNavigationSort(): ?int
    {
        return 1;
    }
    protected static ?string $model = ReportTemplate::class;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return ReportTemplateForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ReportTemplateInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReportTemplatesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListReportTemplates::route('/'),
            'create' => CreateReportTemplate::route('/create'),
            'view'   => ViewReportTemplate::route('/{record}'),
            'edit'   => EditReportTemplate::route('/{record}/edit'),
        ];
    }
}
