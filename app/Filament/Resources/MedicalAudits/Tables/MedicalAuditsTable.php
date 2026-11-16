<?php

namespace App\Filament\Resources\MedicalAudits\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class MedicalAuditsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('audit_number')->label('Audit #')->searchable(),
                    TextColumn::make('audit_type')->label('Type')->badge()->color('success')->searchable(),
                    TextColumn::make('department.name')->label('Department')->sortable(),
                    TextColumn::make('auditor.name')->label('Auditor')->sortable(),
                    TextColumn::make('audit_date')->label('Date')->date()->searchable(),
                    TextColumn::make('risk_level')->label('Risk')->badge()->color('success')->searchable(),
                    TextColumn::make('status')->label('Status')->badge()->color('success')->searchable(),
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
