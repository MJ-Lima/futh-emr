<?php

namespace App\Filament\Resources\PatientPortalUsers\Pages;

use App\Filament\Resources\PatientPortalUsers\PatientPortalUserResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewPatientPortalUser extends ViewRecord
{
    protected static string $resource = PatientPortalUserResource::class;

    protected function getHeaderActions(): array
    {
        $record = $this->getRecord();
        $actions = [];

        if ($record->status === 'pending') {
            $actions[] = Action::make('approve')
                ->label('Approve account')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {
                    $this->getRecord()->markApproved(auth()->id());
                    if ($this->getRecord()->patient && $this->getRecord()->patient->verification_status === 'Pending') {
                        $this->getRecord()->patient->update(['verification_status' => 'Verified', 'verified_at' => now(), 'verified_by' => auth()->id()]);
                    }
                    Notification::make()->title('Patient account approved')->success()->send();
                    $this->refresh();
                });
        }

        if ($record->status === 'active') {
            $actions[] = Action::make('suspend')
                ->label('Suspend account')
                ->icon('heroicon-o-lock-closed')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    $this->getRecord()->update(['status' => 'suspended']);
                    Notification::make()->title('Patient account suspended')->warning()->send();
                    $this->refresh();
                });
        }

        if (in_array($record->status, ['suspended', 'locked'])) {
            $actions[] = Action::make('reactivate')
                ->label('Reactivate account')
                ->icon('heroicon-o-lock-open')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {
                    $this->getRecord()->update(['status' => 'active']);
                    Notification::make()->title('Patient account reactivated')->success()->send();
                    $this->refresh();
                });
        }

        return $actions;
    }
}
