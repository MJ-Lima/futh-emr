<?php

namespace App\Filament\Resources\StaffMessages\Pages;

use App\Filament\Resources\StaffMessages\StaffMessageResource;
use App\Models\StaffMessage;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewStaffMessage extends ViewRecord
{
    protected static string $resource = StaffMessageResource::class;

    public function mount(int | string $record): void
    {
        parent::mount($record);

        // Mark as read when the recipient opens it.
        if ($this->record
            && $this->record->recipient_id === auth()->id()
            && ! $this->record->read_at
        ) {
            $this->record->markRead();
        }
    }

    protected function getHeaderActions(): array
    {
        $record = $this->getRecord();
        $actions = [];

        if ($record && $record->sender_id !== auth()->id()) {
            $actions[] = Action::make('reply')
                ->label('Reply')
                ->icon('heroicon-o-arrow-uturn-left')
                ->form([
                    \Filament\Forms\Components\TextInput::make('subject')
                        ->default('Re: ' . ($record->subject ?: ''))
                        ->required(),
                    \Filament\Forms\Components\Textarea::make('body')->required()->rows(6),
                ])
                ->action(function (array $data) use ($record) {
                    $reply = StaffMessage::create([
                        'hospital_id'  => $record->hospital_id,
                        'sender_id'    => auth()->id(),
                        'recipient_id' => $record->sender_id,
                        'parent_id'    => $record->id,
                        'subject'      => $data['subject'],
                        'body'         => $data['body'],
                        'status'       => 'sent',
                    ]);
                    try {
                        $sender = \App\Models\User::find($record->sender_id);
                        if ($sender && $sender->email) {
                            \Illuminate\Support\Facades\Mail::to($sender->email)
                                ->send(new \App\Mail\StaffMessageReceived($reply));
                        }
                    } catch (\Throwable $e) { /* best-effort */ }
                });
        }

        return $actions;
    }
}
