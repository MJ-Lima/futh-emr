<?php

namespace App\Filament\Resources\BloodDonors\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class BloodDonorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('donor_number')->label('Donor #')->searchable(),
                    TextColumn::make('full_name')->label('Name')->searchable(),
                    TextColumn::make('gender')->label('Gender')->badge()->color('success')->searchable(),
                    TextColumn::make('blood_group')->label('Blood Group')->badge()->color('success')->searchable(),
                    TextColumn::make('rh_factor')->label('Rh')->searchable(),
                    TextColumn::make('total_donations')->label('Donations')->searchable(),
                    TextColumn::make('last_donation_date')->label('Last Donation')->date()->searchable(),
                    IconColumn::make('is_eligible')->label('Eligible')->boolean(),
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
