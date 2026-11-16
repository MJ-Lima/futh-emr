<?php

namespace App\Filament\Resources\CashierTasks\Tables;

use App\Services\CashierTaskService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CashierTasksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'asc')
            ->columns([
                TextColumn::make('task_number')->searchable()->badge()->color('gray'),
                TextColumn::make('patient.full_name')
                    ->label('Billed To')
                    ->searchable()->sortable()
                    ->formatStateUsing(fn ($state, $record) => $record->patient?->full_name
                        ?? ($record->invoice?->customer_name ?? 'Walk-in customer'))
                    ->description(fn ($record) => $record->patient?->hospital_no
                        ?? ($record->invoice?->customer_phone ?? '')),
                TextColumn::make('patient.hospital_no')->label('Hosp No')->toggleable(),
                TextColumn::make('source_type')->badge()->color('info'),
                TextColumn::make('description')->limit(40),
                TextColumn::make('amount_due')->money('NGN')->sortable()->color('success'),
                TextColumn::make('amount_paid')->money('NGN')->toggleable(),
                TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                    'pending'       => 'warning',
                    'in_progress'   => 'info',
                    'paid'          => 'success',
                    'short_paid'    => 'info',
                    'cancelled'     => 'danger',
                    'refunded'      => 'gray',
                    default          => 'gray',
                }),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'pending'      => 'Pending',
                    'in_progress'  => 'In Progress',
                    'paid'         => 'Paid',
                    'short_paid'   => 'Short Paid',
                    'cancelled'    => 'Cancelled',
                    'refunded'     => 'Refunded',
                ]),
                SelectFilter::make('source_type')->options([
                    'Invoice'            => 'Invoice (incl. Bill-a-Service)',
                    'LabOrder'           => 'Lab Order',
                    'RadiologyOrder'     => 'Radiology Order',
                    'Prescription'       => 'Prescription',
                    'AppointmentCardFee' => 'Appointment Card Fee',
                    'Consultation'       => 'Consultation Fee',
                    'NursingService'     => 'Nursing Service',
                ]),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('collect')
                    ->label('Collect payment')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'in_progress', 'short_paid']))
                    ->form([
                        TextInput::make('amount_paid')
                            ->label('Amount received (₦)')
                            ->numeric()
                            ->required()
                            ->default(fn ($record) => (float) $record->amount_due - (float) $record->amount_paid),
                        Select::make('payment_method')
                            ->label('Payment method')
                            ->options([
                                'Cash'           => 'Cash',
                                'Card'           => 'Card',
                                'Bank Transfer'  => 'Bank Transfer',
                                'POS'            => 'POS',
                                'Mobile Money'   => 'Mobile Money',
                                'Insurance'      => 'Insurance',
                                'Online'         => 'Online',
                            ])
                            ->default('Cash')
                            ->required(),
                        TextInput::make('receipt_number')->label('Receipt number (optional)'),
                        Textarea::make('notes')->rows(2),
                    ])
                    ->action(function ($record, array $data) {
                        $service = app(CashierTaskService::class);
                        $service->markPaid($record, $data);
                        Notification::make()
                            ->title('Payment recorded')
                            ->body('₦' . number_format((float) $data['amount_paid'], 2) . ' collected for ' . $record->task_number)
                            ->success()
                            ->send();
                    }),
                Action::make('cancel')
                    ->label('Cancel task')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'in_progress']))
                    ->action(function ($record) {
                        $record->update(['status' => 'cancelled']);
                        Notification::make()->title('Cashier task cancelled')->warning()->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
