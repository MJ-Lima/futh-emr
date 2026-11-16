<?php

namespace App\Filament\Resources\PatientSatisfactionSurveys\Tables;

use App\Models\PatientSatisfactionSurvey;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PatientSatisfactionSurveysTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('survey_number')->label('Survey #')->searchable()->weight('semibold')->copyable(),
            TextColumn::make('survey_date')->label('Date')->date('d M Y')->sortable(),
            TextColumn::make('patient.full_name')->label('Patient')->placeholder('Anonymous')->searchable(),
            TextColumn::make('department_visited')->label('Department')->placeholder('—'),
            TextColumn::make('average_rating')->label('Avg Rating')->badge()
                ->color(fn ($v) => $v >= 4 ? 'success' : ($v >= 3 ? 'info' : 'warning')),
            TextColumn::make('rating_overall')->label('Overall')->badge(),
            TextColumn::make('would_recommend')->label('Recommend')->badge()
                ->formatStateUsing(fn ($s) => $s ? 'Yes' : 'No')
                ->color(fn ($s) => $s ? 'success' : 'danger'),
            TextColumn::make('status')->label('Status')->badge()
                ->formatStateUsing(fn ($s) => PatientSatisfactionSurvey::STATUSES[$s] ?? $s)
                ->color(fn ($s) => PatientSatisfactionSurvey::STATUS_COLORS[$s] ?? 'gray'),
        ])
        ->filters([
            SelectFilter::make('status')->options(PatientSatisfactionSurvey::STATUSES),
        ])
        ->recordActions([ViewAction::make(), EditAction::make()], position: RecordActionsPosition::BeforeColumns)
        ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
        ->defaultSort('survey_date', 'desc');
    }
}
