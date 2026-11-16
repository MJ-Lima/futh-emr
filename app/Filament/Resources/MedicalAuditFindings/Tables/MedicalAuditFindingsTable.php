<?php

namespace App\Filament\Resources\MedicalAuditFindings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class MedicalAuditFindingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('audit.audit_number')->label('Audit')->sortable(),
                    TextColumn::make('finding_number')->label('Finding #')->searchable(),
                    TextColumn::make('category')->label('Category')->badge()->color('success')->searchable(),
                    TextColumn::make('title')->label('Title')->searchable(),
                    TextColumn::make('severity')->label('Severity')->badge()->color('success')->searchable(),
                    TextColumn::make('risk_level')->label('Risk')->badge()->color('success')->searchable(),
                    TextColumn::make('status')->label('Status')->badge()->color('success')->searchable(),
                    TextColumn::make('target_completion_date')->label('Target Date')->date()->searchable(),
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
