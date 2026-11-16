<?php

namespace App\Filament\Resources\PatientSatisfactionSurveys\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use App\Models\PatientSatisfactionSurvey;
class PatientSatisfactionSurveyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Survey Details')->icon('heroicon-m-document-text')->schema([
                Grid::make(3)->schema([
                    Select::make('patient_id')->label('Patient (optional)')
                        ->relationship('patient', 'hospital_no')
                        ->getOptionLabelFromRecordUsing(fn ($r) => "{$r->lastname} {$r->firstname} — {$r->hospital_no}")
                        ->searchable()->preload()->native(false),
                    Select::make('encounter_id')->label('Encounter (optional)')
                        ->relationship('encounter', 'encounter_number')->searchable()->preload()->native(false),
                    DatePicker::make('survey_date')->label('Survey Date')->required()->default(now()),
                    TextInput::make('department_visited')->label('Department Visited')->placeholder('e.g. ANC, SOPD, A&E'),
                    TextInput::make('respondent_name')->label('Respondent Name'),
                    TextInput::make('respondent_phone')->label('Respondent Phone'),
                ]),
            ]),

            Section::make('Service Ratings (1=Very Poor, 5=Excellent)')->icon('heroicon-m-star')->schema([
                Grid::make(4)->schema([
                    Select::make('rating_reception')->label('Reception')->options(PatientSatisfactionSurvey::RATINGS)->native(false),
                    Select::make('rating_nursing_care')->label('Nursing Care')->options(PatientSatisfactionSurvey::RATINGS)->native(false),
                    Select::make('rating_doctor_care')->label('Doctor Care')->options(PatientSatisfactionSurvey::RATINGS)->native(false),
                    Select::make('rating_cleanliness')->label('Cleanliness')->options(PatientSatisfactionSurvey::RATINGS)->native(false),
                    Select::make('rating_waiting_time')->label('Waiting Time')->options(PatientSatisfactionSurvey::RATINGS)->native(false),
                    Select::make('rating_pharmacy')->label('Pharmacy')->options(PatientSatisfactionSurvey::RATINGS)->native(false),
                    Select::make('rating_lab_service')->label('Lab Service')->options(PatientSatisfactionSurvey::RATINGS)->native(false),
                    Select::make('rating_overall')->label('Overall Experience')->options(PatientSatisfactionSurvey::RATINGS)->native(false),
                ]),
            ]),

            Section::make('Feedback')->icon('heroicon-m-chat-bubble-left-right')->schema([
                Textarea::make('best_experience')->label('Best Experience')->rows(2),
                Textarea::make('worst_experience')->label('Worst Experience')->rows(2),
                Textarea::make('suggestions')->label('Suggestions for Improvement')->rows(3),
                Textarea::make('complaints')->label('Complaints')->rows(3),
                Toggle::make('would_recommend')->label('Would you recommend this hospital to others?'),
            ]),

            Section::make('Review')->icon('heroicon-m-check-badge')->schema([
                Grid::make(2)->schema([
                    Select::make('status')->label('Status')->options(PatientSatisfactionSurvey::STATUSES)->default('Pending Review')->native(false),
                    TextInput::make('collected_by')->label('Collected By'),
                ]),
                Textarea::make('review_notes')->label('Review Notes')->rows(2),
            ]),
        ]);
    }
}
