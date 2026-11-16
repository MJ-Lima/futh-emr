<?php

namespace App\Filament\Resources\BloodDonations\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class BloodDonationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('donation_number')->label('Donation #')->searchable(),
                    TextColumn::make('donor.full_name')->label('Donor')->sortable(),
                    TextColumn::make('donor.blood_group')->label('Group')->sortable(),
                    TextColumn::make('donation_date')->label('Date')->date()->searchable(),
                    TextColumn::make('donation_type')->label('Type')->badge()->color('success')->searchable(),
                    TextColumn::make('volume_collected_ml')->label('Volume (ml)')->searchable(),
                    TextColumn::make('collector.name')->label('Collected By')->sortable(),
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
