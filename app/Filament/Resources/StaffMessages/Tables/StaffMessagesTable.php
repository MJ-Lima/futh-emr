<?php

namespace App\Filament\Resources\StaffMessages\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;
use App\Mail\StaffMessageReceived;
use App\Models\StaffMessage;

class StaffMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('sender.name')->label('From')->searchable()->sortable(),
                TextColumn::make('recipient.name')->label('To')->placeholder('—')->searchable()->sortable(),
                TextColumn::make('subject')->searchable()->limit(50)->placeholder('(no subject)'),
                TextColumn::make('priority')->badge()->color(fn ($state) => match ($state) {
                    'Urgent'     => 'danger',
                    'Important'  => 'warning',
                    default       => 'gray',
                }),
                TextColumn::make('read_at')->dateTime()->placeholder('Unread')->toggleable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('priority')->options([
                    'Normal'     => 'Normal',
                    'Important'  => 'Important',
                    'Urgent'    => 'Urgent',
                ]),
                SelectFilter::make('status')->options([
                    'sent'       => 'Sent',
                    'read'       => 'Read',
                    'archived'   => 'Archived',
                ]),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('reply')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->form([
                        TextInput::make('subject')->default(fn (StaffMessage $record) => 'Re: ' . ($record->subject ?: ''))->required(),
                        Textarea::make('body')->required()->rows(6),
                    ])
                    ->action(function (StaffMessage $record, array $data) {
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
                            if ($record->sender && $record->sender->email) {
                                Mail::to($record->sender->email)->send(new StaffMessageReceived($reply));
                            }
                        } catch (\Throwable $e) { /* email is best-effort */ }
                    })
                    ->visible(fn (StaffMessage $record) => $record->sender_id !== auth()->id()),
            ])
            ->toolbarActions([
                Action::make('compose')
                    ->label('Compose message')
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->form([
                        Select::make('recipient_id')
                            ->relationship('recipient', 'name', fn ($q) => $q->orderBy('name'))
                            ->label('To')
                            ->searchable()
                            ->required(),
                        TextInput::make('subject')->label('Subject')->maxLength(200),
                        Select::make('priority')->options([
                            'Normal'     => 'Normal',
                            'Important'  => 'Important',
                            'Urgent'    => 'Urgent',
                        ])->default('Normal')->required(),
                        Textarea::make('body')->label('Message')->required()->rows(8)->maxLength(5000),
                    ])
                    ->action(function (array $data) {
                        $msg = StaffMessage::create(array_merge($data, [
                            'hospital_id' => auth()->user()?->hospital_id,
                            'sender_id'  => auth()->id(),
                            'status'     => 'sent',
                        ]));
                        try {
                            $recipient = \App\Models\User::find($data['recipient_id']);
                            if ($recipient && $recipient->email) {
                                Mail::to($recipient->email)->send(new StaffMessageReceived($msg));
                            }
                        } catch (\Throwable $e) { /* best-effort */ }
                    }),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
