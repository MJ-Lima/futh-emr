<?php

namespace App\Filament\Resources\PatientPortalUsers\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PatientPortalUsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('username')->searchable()->sortable(),
                TextColumn::make('patient.full_name')->label('Patient')->searchable()->sortable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('patient.hospital.name')->label('Hospital')->toggleable(),
                TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                    'active'    => 'success',
                    'pending'   => 'warning',
                    'suspended' => 'danger',
                    'locked'    => 'danger',
                    default      => 'gray',
                }),
                TextColumn::make('last_login_at')->dateTime()->toggleable()->sortable(),
                TextColumn::make('created_at')->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'pending'   => 'Pending',
                    'active'    => 'Active',
                    'suspended' => 'Suspended',
                    'locked'    => 'Locked',
                ]),
            ])
            ->recordActions([
                ViewAction::make(),

                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Approve patient portal account')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record, $livewire) {
                        $record->markApproved(auth()->id());
                        if ($record->patient && $record->patient->verification_status === 'Pending') {
                            $record->patient->update(['verification_status' => 'Verified', 'verified_at' => now(), 'verified_by' => auth()->id()]);
                        }
                        Notification::make()->title('Patient account approved')->success()->send();
                        $livewire->refreshTable();
                    }),

                Action::make('suspend')
                    ->label('Suspend')
                    ->icon('heroicon-o-lock-closed')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'active')
                    ->action(function ($record, $livewire) {
                        $record->update(['status' => 'suspended']);
                        Notification::make()->title('Patient account suspended')->warning()->send();
                        $livewire->refreshTable();
                    }),

                Action::make('reactivate')
                    ->label('Reactivate')
                    ->icon('heroicon-o-lock-open')
                    ->color('success')
                    ->visible(fn ($record) => in_array($record->status, ['suspended', 'locked']))
                    ->action(function ($record, $livewire) {
                        $record->update(['status' => 'active']);
                        Notification::make()->title('Patient account reactivated')->success()->send();
                        $livewire->refreshTable();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
