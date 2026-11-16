<?php

namespace App\Filament\Resources\PatientInsurance\Pages;

use App\Filament\Resources\PatientInsurance\PatientInsuranceResource;
use App\Models\InsuranceProvider;
use App\Models\InsurancePlan;
use App\Models\PatientInsurance;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Schema;

class ListPatientInsurance extends ListRecords
{
    protected static string $resource = PatientInsuranceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Bulk Activate by Provider/Plan
            Action::make('activateByGroup')
                ->label('Activate by Provider / Plan')
                ->icon('heroicon-m-bolt')
                ->color('success')
                ->modalHeading('Activate Patients by Insurance Provider or Plan')
                ->modalDescription('Select a provider and/or plan, then choose the action. All matching patient policies will be updated.')
                ->form([
                    Select::make('provider_id')
                        ->label('Insurance Provider')
                        ->relationship('provider', 'name')
                        ->searchable()->preload()->native(false)
                        ->live(),
                    Select::make('plan_id')
                        ->label('Insurance Plan (optional — leave blank for all plans under the provider)')
                        ->options(function (\Filament\Forms\Get $get) {
                            $providerId = $get('provider_id');
                            if (!$providerId) return [];
                            return InsurancePlan::where('insurance_provider_id', $providerId)
                                ->pluck('name', 'id')->toArray();
                        })
                        ->searchable()->native(false)
                        ->live(),
                    ToggleButtons::make('action_type')
                        ->label('Action')
                        ->options([
                            'activate' => 'Activate (Set to Active + Verified)',
                            'verify' => 'Verify Only (Set to Verified, keep status)',
                            'suspend' => 'Suspend (Set to Suspended)',
                            'cancel' => 'Cancel (Set to Cancelled)',
                        ])
                        ->colors([
                            'activate' => 'success',
                            'verify' => 'info',
                            'suspend' => 'warning',
                            'cancel' => 'danger',
                        ])
                        ->icons([
                            'activate' => 'heroicon-m-check-circle',
                            'verify' => 'heroicon-m-shield-check',
                            'suspend' => 'heroicon-m-pause-circle',
                            'cancel' => 'heroicon-m-x-circle',
                        ])
                        ->inline()
                        ->default('activate')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $query = PatientInsurance::query();
                    if (!empty($data['provider_id'])) {
                        $query->where('insurance_provider_id', $data['provider_id']);
                    }
                    if (!empty($data['plan_id'])) {
                        $query->where('insurance_plan_id', $data['plan_id']);
                    }

                    $count = $query->count();
                    if ($count === 0) {
                        Notification::make()->warning()
                            ->title('No policies found')
                            ->body('No patient insurance policies match the selected provider/plan.')
                            ->send();
                        return;
                    }

                    $updateData = match ($data['action_type']) {
                        'activate' => [
                            'status' => 'Active',
                            'verification_status' => 'Verified',
                            'verified_at' => now(),
                            'verified_by' => auth()->id(),
                        ],
                        'verify' => [
                            'verification_status' => 'Verified',
                            'verified_at' => now(),
                            'verified_by' => auth()->id(),
                        ],
                        'suspend' => ['status' => 'Suspended'],
                        'cancel' => ['status' => 'Cancelled'],
                        default => [],
                    };

                    if (!empty($updateData)) {
                        $query->update($updateData);
                    }

                    $actionLabel = match ($data['action_type']) {
                        'activate' => 'activated',
                        'verify' => 'verified',
                        'suspend' => 'suspended',
                        'cancel' => 'cancelled',
                        default => 'updated',
                    };

                    Notification::make()->success()
                        ->title("{$count} policies {$actionLabel}")
                        ->body("All patient insurance policies matching the selected provider/plan have been {$actionLabel}.")
                        ->persistent()
                        ->send();
                }),

            CreateAction::make()->icon('heroicon-m-plus')->label('New Policy'),
        ];
    }
}
