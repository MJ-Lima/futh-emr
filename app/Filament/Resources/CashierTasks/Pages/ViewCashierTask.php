<?php

namespace App\Filament\Resources\CashierTasks\Pages;

use App\Filament\Resources\CashierTasks\CashierTaskResource;
use App\Services\CashierTaskService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewCashierTask extends ViewRecord
{
    protected static string $resource = CashierTaskResource::class;

    protected function getHeaderActions(): array
    {
        $record = $this->getRecord();
        $actions = [];

        if (in_array($record->status, ['pending', 'in_progress', 'short_paid'])) {
            $actions[] = Action::make('collect')
                ->label('Collect payment')
                ->icon('heroicon-o-banknotes')
                ->color('success')
                ->form([
                    TextInput::make('amount_paid')
                        ->label('Amount received (₦)')
                        ->numeric()
                        ->required()
                        ->default((float) $record->amount_due - (float) $record->amount_paid),
                    Select::make('payment_method')
                        ->label('Payment method')
                        ->options([
                            'Cash'          => 'Cash',
                            'Card'          => 'Card',
                            'Bank Transfer' => 'Bank Transfer',
                            'POS'           => 'POS',
                            'Mobile Money'  => 'Mobile Money',
                            'Insurance'     => 'Insurance',
                            'Online'        => 'Online',
                        ])
                        ->default('Cash')
                        ->required(),
                    TextInput::make('receipt_number')->label('Receipt number (optional)'),
                    Textarea::make('notes')->rows(2),
                ])
                ->action(function (array $data) use ($record) {
                    app(CashierTaskService::class)->markPaid($record, $data);
                    Notification::make()
                        ->title('Payment recorded')
                        ->body('₦' . number_format((float) $data['amount_paid'], 2) . ' collected for ' . $record->task_number)
                        ->success()
                        ->send();
                    $this->refresh();
                });

            $actions[] = Action::make('cancel')
                ->label('Cancel task')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () use ($record) {
                    $record->update(['status' => 'cancelled']);
                    Notification::make()->title('Cashier task cancelled')->warning()->send();
                    $this->refresh();
                });
        }

        return $actions;
    }
}
