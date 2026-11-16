<?php

namespace App\Filament\Resources\AuditFollowUps\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class AuditFollowUpsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('follow_up_number')->label('Follow-up #')->searchable(),
                    TextColumn::make('audit_type')->label('Audit Type')->badge()->color('success')->searchable(),
                    TextColumn::make('follow_up_date')->label('Date')->date()->searchable(),
                    TextColumn::make('progress_status')->label('Progress')->badge()->color('success')->searchable(),
                    TextColumn::make('completion_percentage')->label('% Complete')->searchable(),
                    TextColumn::make('follower.name')->label('By')->sortable(),
                    TextColumn::make('next_follow_up_date')->label('Next Follow-up')->date()->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ], position: RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
