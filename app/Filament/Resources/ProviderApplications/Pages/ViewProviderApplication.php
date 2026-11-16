<?php

namespace App\Filament\Resources\ProviderApplications\Pages;

use App\Filament\Resources\ProviderApplications\ProviderApplicationResource;
use App\Services\ProviderApprovalService;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewProviderApplication extends ViewRecord
{
    protected static string $resource = ProviderApplicationResource::class;

    protected function getHeaderActions(): array
    {
        $record = $this->getRecord();

        if (! in_array($record->status, ['pending', 'under_review'])) {
            return [];
        }

        return [
            Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Approve provider application')
                ->modalDescription('This will create the provider account and enable login. Continue?')
                ->action(function () {
                    $result = app(ProviderApprovalService::class)->approve($this->getRecord(), auth()->id());
                    Notification::make()
                        ->title('Application approved')
                        ->body($this->getRecord()->account_type === 'doctor'
                            ? 'Doctor account created: ' . $result['user']->email
                            : 'Hospital "' . $result['hospital']?->name . '" and admin account created.')
                        ->success()
                        ->send();
                    $this->refresh();
                }),

            Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->form([
                    Textarea::make('review_notes')->label('Reason for rejection')->required()->rows(3),
                ])
                ->action(function (array $data) {
                    app(ProviderApprovalService::class)->reject($this->getRecord(), auth()->id(), $data['review_notes']);
                    Notification::make()->title('Application rejected')->success()->send();
                    $this->refresh();
                }),
        ];
    }
}
