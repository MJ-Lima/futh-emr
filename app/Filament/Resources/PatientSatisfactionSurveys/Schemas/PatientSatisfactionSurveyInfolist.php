<?php

namespace App\Filament\Resources\PatientSatisfactionSurveys\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class PatientSatisfactionSurveyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Survey Details')->schema([
                TextEntry::make('survey_number')->label('Survey #')->badge()->color('info')->copyable(),
                TextEntry::make('survey_date')->label('Date')->date('d M Y'),
                TextEntry::make('department_visited')->label('Department'),
                TextEntry::make('respondent_name')->label('Respondent'),
                TextEntry::make('average_rating')->label('Avg Rating')->badge(),
                TextEntry::make('status')->badge(),
            ])->columns(3),
            Section::make('Ratings')->schema([
                TextEntry::make('rating_overall')->label('Overall')->badge(),
                TextEntry::make('rating_reception')->label('Reception')->badge(),
                TextEntry::make('rating_nursing_care')->label('Nursing')->badge(),
                TextEntry::make('rating_doctor_care')->label('Doctor')->badge(),
                TextEntry::make('rating_cleanliness')->label('Cleanliness')->badge(),
                TextEntry::make('rating_waiting_time')->label('Waiting Time')->badge(),
                TextEntry::make('rating_pharmacy')->label('Pharmacy')->badge(),
                TextEntry::make('rating_lab_service')->label('Lab')->badge(),
            ])->columns(4),
            Section::make('Feedback')->schema([
                TextEntry::make('best_experience')->placeholder('—')->columnSpanFull(),
                TextEntry::make('worst_experience')->placeholder('—')->columnSpanFull(),
                TextEntry::make('suggestions')->placeholder('—')->columnSpanFull(),
                TextEntry::make('complaints')->placeholder('—')->columnSpanFull(),
                TextEntry::make('would_recommend')->badge()->placeholder('—'),
            ]),
        ]);
    }
}
