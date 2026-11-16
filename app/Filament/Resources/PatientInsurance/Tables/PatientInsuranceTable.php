<?php

namespace App\Filament\Resources\PatientInsurance\Tables;

use App\Models\PatientInsurance;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class PatientInsuranceTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('policy_number')->label('Policy #')->searchable()->weight('semibold')->copyable(),
                TextColumn::make('patient.full_name')->label('Patient')->sortable()->searchable()
                    ->description(fn ($r) => $r->patient?->hospital_no ?? ''),
                TextColumn::make('provider.name')->label('Provider')->sortable()->searchable(),
                TextColumn::make('plan.name')->label('Plan')->sortable()->searchable(),
                TextColumn::make('plan.tier')->label('Tier')->badge()->color('info'),
                TextColumn::make('principal_relationship')->label('Relationship')->badge(),
                TextColumn::make('effective_date')->label('Effective')->date('d M Y')->sortable(),
                TextColumn::make('expiry_date')->label('Expiry')->date('d M Y')->sortable(),
                TextColumn::make('verification_status')->label('Verification')->badge()
                    ->color(fn ($s) => match($s) {
                        'Verified' => 'success',
                        'Pending' => 'warning',
                        'Rejected' => 'danger',
                        'Expired' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('status')->label('Status')->badge()
                    ->color(fn ($s) => match($s) {
                        'Active' => 'success',
                        'Suspended' => 'warning',
                        'Expired' => 'gray',
                        'Cancelled' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('remaining_benefit')->label('Benefit Left')->money('NGN')->toggleable(),
            ])
            ->filters([
                SelectFilter::make('insurance_provider_id')
                    ->label('Provider')
                    ->relationship('provider', 'name')
                    ->searchable()->preload(),
                SelectFilter::make('insurance_plan_id')
                    ->label('Plan')
                    ->relationship('plan', 'name')
                    ->searchable()->preload(),
                SelectFilter::make('verification_status')
                    ->label('Verification')
                    ->options([
                        'Pending' => 'Pending',
                        'Verified' => 'Verified',
                        'Rejected' => 'Rejected',
                        'Expired' => 'Expired',
                    ]),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Active' => 'Active',
                        'Suspended' => 'Suspended',
                        'Expired' => 'Expired',
                        'Cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('principal_relationship')
                    ->label('Relationship')
                    ->options([
                        'Self' => 'Self',
                        'Spouse' => 'Spouse',
                        'Child' => 'Child',
                        'Parent' => 'Parent',
                        'Sibling' => 'Sibling',
                        'Dependant' => 'Dependant',
                    ]),
            ])
            ->groups([
                Group::make('provider.name')->label('Provider')->collapsible(),
                Group::make('plan.name')->label('Plan')->collapsible(),
                Group::make('status')->label('Status')->collapsible(),
                Group::make('verification_status')->label('Verification')->collapsible(),
            ])
            ->defaultGroup('provider.name')
            ->recordActions([
                // Single-record Activate
                Action::make('activate')
                    ->label('Activate')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->button()
                    ->requiresConfirmation()
                    ->modalDescription('Activate this patient\'s insurance policy. This will set status to Active and verification to Verified.')
                    ->visible(fn (PatientInsurance $r) => $r->status !== 'Active')
                    ->action(function (PatientInsurance $r) {
                        $r->update([
                            'status' => 'Active',
                            'verification_status' => 'Verified',
                            'verified_at' => now(),
                            'verified_by' => auth()->id(),
                        ]);
                        Notification::make()->success()
                            ->title("Policy {$r->policy_number} activated")
                            ->body("Patient: {$r->patient?->full_name} — Provider: {$r->provider?->name}")
                            ->send();
                    }),

                // Single-record Suspend
                Action::make('suspend')
                    ->label('Suspend')
                    ->icon('heroicon-m-pause-circle')
                    ->color('warning')
                    ->button()
                    ->requiresConfirmation()
                    ->modalDescription('Suspend this patient\'s insurance policy. They will not be able to access insurance services until reactivated.')
                    ->visible(fn (PatientInsurance $r) => $r->status === 'Active')
                    ->action(function (PatientInsurance $r) {
                        $r->update(['status' => 'Suspended']);
                        Notification::make()->warning()
                            ->title("Policy {$r->policy_number} suspended")
                            ->send();
                    }),

                // Single-record Deactivate (Cancel)
                Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->button()
                    ->requiresConfirmation()
                    ->modalDescription('Cancel this patient\'s insurance policy. This is a permanent action.')
                    ->visible(fn (PatientInsurance $r) => $r->status !== 'Cancelled')
                    ->action(function (PatientInsurance $r) {
                        $r->update(['status' => 'Cancelled']);
                        Notification::make()->danger()
                            ->title("Policy {$r->policy_number} cancelled")
                            ->send();
                    }),

                ViewAction::make(),
                EditAction::make(),
            ], position: RecordActionsPosition::BeforeColumns)
            ->bulkActions([
                // BULK: Activate selected patients
                BulkAction::make('activateSelected')
                    ->label('Activate Selected')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Activate Selected Patients')
                    ->modalDescription('This will set all selected policies to Active status and mark them as Verified. Use this to activate a group of patients by their plan or provider.')
                    ->deselectAfterCompletion()
                    ->action(function (\Illuminate\Support\Collection $records) {
                        $count = 0;
                        foreach ($records as $r) {
                            if ($r->status !== 'Active') {
                                $r->update([
                                    'status' => 'Active',
                                    'verification_status' => 'Verified',
                                    'verified_at' => now(),
                                    'verified_by' => auth()->id(),
                                ]);
                                $count++;
                            }
                        }
                        Notification::make()->success()
                            ->title("{$count} patient insurance policies activated")
                            ->send();
                    }),

                // BULK: Verify selected (verification only, no activation)
                BulkAction::make('verifySelected')
                    ->label('Verify Selected')
                    ->icon('heroicon-m-shield-check')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalDescription('Mark all selected policies as Verified. This does not change their Active/Suspended status.')
                    ->deselectAfterCompletion()
                    ->action(function (\Illuminate\Support\Collection $records) {
                        $count = 0;
                        foreach ($records as $r) {
                            if ($r->verification_status !== 'Verified') {
                                $r->update([
                                    'verification_status' => 'Verified',
                                    'verified_at' => now(),
                                    'verified_by' => auth()->id(),
                                ]);
                                $count++;
                            }
                        }
                        Notification::make()->success()
                            ->title("{$count} policies verified")
                            ->send();
                    }),

                // BULK: Suspend selected
                BulkAction::make('suspendSelected')
                    ->label('Suspend Selected')
                    ->icon('heroicon-m-pause-circle')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalDescription('Suspend all selected insurance policies. Patients will not be able to access insurance services.')
                    ->deselectAfterCompletion()
                    ->action(function (\Illuminate\Support\Collection $records) {
                        $count = 0;
                        foreach ($records as $r) {
                            if ($r->status === 'Active') {
                                $r->update(['status' => 'Suspended']);
                                $count++;
                            }
                        }
                        Notification::make()->warning()
                            ->title("{$count} policies suspended")
                            ->send();
                    }),

                // BULK: Cancel selected
                BulkAction::make('cancelSelected')
                    ->label('Cancel Selected')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalDescription('Cancel all selected insurance policies. This is a permanent action.')
                    ->deselectAfterCompletion()
                    ->action(function (\Illuminate\Support\Collection $records) {
                        $count = 0;
                        foreach ($records as $r) {
                            if ($r->status !== 'Cancelled') {
                                $r->update(['status' => 'Cancelled']);
                                $count++;
                            }
                        }
                        Notification::make()->danger()
                            ->title("{$count} policies cancelled")
                            ->send();
                    }),

                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
