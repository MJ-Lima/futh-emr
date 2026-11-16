<?php

namespace App\Filament\Resources\PatientPortalMessages\Pages;

use App\Filament\Resources\PatientPortalMessages\PatientPortalMessageResource;
use App\Models\PatientPortalMessage;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewPatientPortalMessage extends ViewRecord
{
    protected static string $resource = PatientPortalMessageResource::class;

    public function mount(int | string $record): void
    {
        parent::mount($record);

        $this->record->load(['patientPortalUser', 'patient', 'hospital', 'staffUser']);

        // Mark inbound messages as read when an admin opens them
        if ($this->record->direction === 'inbound' && ! $this->record->read_at) {
            $this->record->update(['read_at' => now(), 'status' => 'read']);
        }
    }

    protected function getHeaderActions(): array
    {
        $record = $this->getRecord();

        if ($record->direction !== 'inbound') {
            return [];
        }

        return [
            Action::make('reply')
                ->label('Reply to patient')
                ->icon('heroicon-o-chat-bubble-left-ellipsis')
                ->color('success')
                ->form([
                    Textarea::make('body')->label('Reply')->required()->rows(6)->default('Re: ' . ($record->subject ?: '')),
                ])
                ->action(function (array $data) use ($record) {
                    PatientPortalMessage::create([
                        'patient_portal_user_id' => $record->patient_portal_user_id,
                        'patient_id'              => $record->patient_id,
                        'hospital_id'             => $record->hospital_id,
                        'staff_user_id'           => auth()->id(),
                        'direction'              => 'outbound',
                        'status'                 => 'delivered',
                        'subject'                => 'Re: ' . ($record->subject ?: ''),
                        'body'                   => $data['body'],
                    ]);
                    $record->update(['status' => 'replied']);
                    Notification::make()->title('Reply sent to patient')->success()->send();
                    $this->refresh();
                }),
        ];
    }
}
