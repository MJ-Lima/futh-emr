<?php

namespace App\Filament\Resources\ClinicTemplates;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\ClinicTemplates\Pages\CreateClinicTemplate;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\ClinicTemplates\Pages\EditClinicTemplate;
use App\Filament\Resources\ClinicTemplates\Pages\ListClinicTemplates;
use App\Filament\Resources\ClinicTemplates\Pages\ViewClinicTemplate;
use App\Filament\Resources\ClinicTemplates\Schemas\ClinicTemplateForm;
use App\Filament\Resources\ClinicTemplates\Schemas\ClinicTemplateInfolist;
use App\Filament\Resources\ClinicTemplates\Tables\ClinicTemplatesTable;
use App\Models\ClinicTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ClinicTemplatesResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedDocumentText;
    }

    public static function getNavigationGroup(): string
    {
        return 'Administration';
    }
    public static function getNavigationLabel(): string
    {
        return 'Clinic Templates';
    }

    public static function getNavigationSort(): ?int
    {
        return 22;
    }
    protected static ?string $model = ClinicTemplate::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ClinicTemplateForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ClinicTemplateInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClinicTemplatesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListClinicTemplates::route('/'),
            'create' => CreateClinicTemplate::route('/create'),
            'view'   => ViewClinicTemplate::route('/{record}'),
            'edit'   => EditClinicTemplate::route('/{record}/edit'),
        ];
    }
}
