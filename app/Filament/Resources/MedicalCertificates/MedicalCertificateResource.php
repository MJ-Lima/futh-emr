<?php

namespace App\Filament\Resources\MedicalCertificates;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\MedicalCertificates\Pages\CreateMedicalCertificate;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\MedicalCertificates\Pages\EditMedicalCertificate;
use App\Filament\Resources\MedicalCertificates\Pages\ListMedicalCertificates;
use App\Filament\Resources\MedicalCertificates\Pages\ViewMedicalCertificate;
use App\Filament\Resources\MedicalCertificates\Schemas\MedicalCertificateForm;
use App\Filament\Resources\MedicalCertificates\Schemas\MedicalCertificateInfolist;
use App\Filament\Resources\MedicalCertificates\Tables\MedicalCertificatesTable;
use App\Models\MedicalCertificate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MedicalCertificateResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedDocumentCheck;
    }

    public static function getNavigationGroup(): string
    {
        return 'Clinical';
    }
    public static function getNavigationLabel(): string
    {
        return 'Medical Certificates';
    }

    public static function getNavigationSort(): ?int
    {
        return 12;
    }
    protected static ?string $model = MedicalCertificate::class;

    protected static ?string $recordTitleAttribute = 'certificate_number';

    public static function form(Schema $schema): Schema
    {
        return MedicalCertificateForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MedicalCertificateInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MedicalCertificatesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListMedicalCertificates::route('/'),
            'create' => CreateMedicalCertificate::route('/create'),
            'view'   => ViewMedicalCertificate::route('/{record}'),
            'edit'   => EditMedicalCertificate::route('/{record}/edit'),
        ];
    }
}
