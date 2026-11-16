<?php

namespace App\Filament\Resources\PatientPortalMessages\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PatientPortalMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')->dateTime()->sortable(),
                TextColumn::make('direction')->badge()->color(fn ($state) => $state === 'inbound' ? 'success' : 'info'),
                TextColumn::make('hospital.name')->label('Hospital')->toggleable(),
                TextColumn::make('patientPortalUser.patient.full_name')->label('Patient')->searchable(),
                TextColumn::make('subject')->searchable()->limit(40),
                TextColumn::make('staffUser.name')->label('Handled by')->toggleable(),
                TextColumn::make('read_at')->dateTime()->placeholder('Unread')->toggleable(),
                TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                    'sent'      => 'warning',
                    'delivered' => 'info',
                    'read'      => 'success',
                    'replied'   => 'success',
                    default       => 'gray',
                }),
            ])
            ->filters([
                SelectFilter::make('direction')->options(['inbound' => 'From patients', 'outbound' => 'From staff']),
                SelectFilter::make('status')->options([
                    'sent'      => 'Sent',
                    'delivered' => 'Delivered',
                    'read'      => 'Read',
                    'replied'   => 'Replied',
                ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
