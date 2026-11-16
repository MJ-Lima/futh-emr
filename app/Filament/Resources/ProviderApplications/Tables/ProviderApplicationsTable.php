<?php

namespace App\Filament\Resources\ProviderApplications\Tables;

use App\Services\ProviderApprovalService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProviderApplicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('application_number')->searchable()->badge()->color('gray'),
                TextColumn::make('account_type')->badge()->color(fn ($state) => $state === 'doctor' ? 'success' : 'info'),
                TextColumn::make('display_name')->label('Applicant / Facility')->searchable(),
                TextColumn::make('email')->searchable()->toggleable(),
                TextColumn::make('phone')->toggleable(),
                TextColumn::make('specialty')->label('Specialty')->toggleable()->visible(fn ($livewire) => true),
                TextColumn::make('facility_state')->label('State')->toggleable(),
                TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                    'pending'      => 'warning',
                    'under_review' => 'warning',
                    'approved'     => 'success',
                    'rejected'     => 'danger',
                    'withdrawn'   => 'gray',
                    default        => 'gray',
                }),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(),
            ])
            ->filters([
                SelectFilter::make('account_type')->options([
                    'doctor'   => 'Doctor',
                    'hospital' => 'Hospital',
                    'clinic'   => 'Clinic',
                ]),
                SelectFilter::make('status')->options([
                    'pending'      => 'Pending',
                    'under_review' => 'Under Review',
                    'approved'     => 'Approved',
                    'rejected'     => 'Rejected',
                    'withdrawn'   => 'Withdrawn',
                ]),
            ])
            ->recordActions([
                ViewAction::make(),

                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Approve provider application')
                    ->modalDescription(fn ($record) => 'This will create a ' . ($record->account_type === 'doctor' ? 'doctor user account' : 'hospital record + admin user account') . ' and enable login. Continue?')
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'under_review']))
                    ->action(function ($record, $livewire) {
                        $result = app(ProviderApprovalService::class)->approve($record, auth()->id());
                        Notification::make()
                            ->title('Application approved')
                            ->body($record->account_type === 'doctor'
                                ? 'Doctor account created: ' . $result['user']->email
                                : 'Hospital "' . $result['hospital']?->name . '" and admin account created.')
                            ->success()
                            ->send();
                        $livewire->refreshTable();
                    }),

                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'under_review']))
                    ->form([
                        Textarea::make('review_notes')->label('Reason for rejection')->required()->rows(3),
                    ])
                    ->action(function ($record, array $data, $livewire) {
                        app(ProviderApprovalService::class)->reject($record, auth()->id(), $data['review_notes']);
                        Notification::make()->title('Application rejected')->success()->send();
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
