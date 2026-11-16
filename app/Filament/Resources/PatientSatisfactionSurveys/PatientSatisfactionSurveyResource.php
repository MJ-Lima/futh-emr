<?php

namespace App\Filament\Resources\PatientSatisfactionSurveys;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\PatientSatisfactionSurveys\Pages\CreatePatientSatisfactionSurvey;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\PatientSatisfactionSurveys\Pages\EditPatientSatisfactionSurvey;
use App\Filament\Resources\PatientSatisfactionSurveys\Pages\ListPatientSatisfactionSurveys;
use App\Filament\Resources\PatientSatisfactionSurveys\Pages\ViewPatientSatisfactionSurvey;
use App\Filament\Resources\PatientSatisfactionSurveys\Schemas\PatientSatisfactionSurveyForm;
use App\Filament\Resources\PatientSatisfactionSurveys\Schemas\PatientSatisfactionSurveyInfolist;
use App\Filament\Resources\PatientSatisfactionSurveys\Tables\PatientSatisfactionSurveysTable;
use App\Models\PatientSatisfactionSurvey;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PatientSatisfactionSurveyResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedStar;
    }

    public static function getNavigationGroup(): string
    {
        return 'Clinical';
    }
    public static function getNavigationLabel(): string
    {
        return 'Satisfaction Surveys';
    }

    public static function getNavigationSort(): ?int
    {
        return 17;
    }
    protected static ?string $model = PatientSatisfactionSurvey::class;

    protected static ?string $recordTitleAttribute = 'survey_number';

    public static function form(Schema $schema): Schema { return PatientSatisfactionSurveyForm::configure($schema); }
    public static function infolist(Schema $schema): Schema { return PatientSatisfactionSurveyInfolist::configure($schema); }
    public static function table(Table $table): Table { return PatientSatisfactionSurveysTable::configure($table); }

    public static function getPages(): array {
        return [
            'index' => ListPatientSatisfactionSurveys::route('/'),
            'create' => CreatePatientSatisfactionSurvey::route('/create'),
            'view' => ViewPatientSatisfactionSurvey::route('/{record}'),
            'edit' => EditPatientSatisfactionSurvey::route('/{record}/edit'),
        ];
    }
}
