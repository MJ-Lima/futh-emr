<?php

namespace App\Filament\Resources\PresentingComplaints;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\PresentingComplaints\Pages\CreatePresentingComplaint;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\PresentingComplaints\Pages\EditPresentingComplaint;
use App\Filament\Resources\PresentingComplaints\Pages\ListPresentingComplaints;
use App\Filament\Resources\PresentingComplaints\Pages\ViewPresentingComplaint;
use App\Filament\Resources\PresentingComplaints\Schemas\PresentingComplaintForm;
use App\Filament\Resources\PresentingComplaints\Schemas\PresentingComplaintInfolist;
use App\Filament\Resources\PresentingComplaints\Tables\PresentingComplaintsTable;
use App\Models\PresentingComplaint;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PresentingComplaintsResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedClipboard;
    }

    public static function getNavigationGroup(): string
    {
        return 'Administration';
    }
    public static function getNavigationLabel(): string
    {
        return 'Presenting Complaints';
    }

    public static function getNavigationSort(): ?int
    {
        return 20;
    }
    protected static ?string $model = PresentingComplaint::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PresentingComplaintForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PresentingComplaintInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PresentingComplaintsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPresentingComplaints::route('/'),
            'create' => CreatePresentingComplaint::route('/create'),
            'view'   => ViewPresentingComplaint::route('/{record}'),
            'edit'   => EditPresentingComplaint::route('/{record}/edit'),
        ];
    }
}
