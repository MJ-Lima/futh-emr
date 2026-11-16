<?php

namespace App\Filament\Resources\LabPanels;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\LabPanels\Pages\CreateLabPanel;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\LabPanels\Pages\EditLabPanel;
use App\Filament\Resources\LabPanels\Pages\ListLabPanels;
use App\Filament\Resources\LabPanels\Pages\ViewLabPanel;
use App\Filament\Resources\LabPanels\Schemas\LabPanelForm;
use App\Filament\Resources\LabPanels\Schemas\LabPanelInfolist;
use App\Filament\Resources\LabPanels\Tables\LabPanelsTable;
use App\Models\LabPanel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LabPanelResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedBeaker;
    }

    public static function getNavigationGroup(): string
    {
        return 'Laboratory';
    }
    public static function getNavigationSort(): ?int
    {
        return 1;
    }
    protected static ?string $model = LabPanel::class;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return LabPanelForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LabPanelInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LabPanelsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListLabPanels::route('/'),
            'create' => CreateLabPanel::route('/create'),
            'view'   => ViewLabPanel::route('/{record}'),
            'edit'   => EditLabPanel::route('/{record}/edit'),
        ];
    }
}
