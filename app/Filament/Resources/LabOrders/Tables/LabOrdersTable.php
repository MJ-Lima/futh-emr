<?php

namespace App\Filament\Resources\LabOrders\Tables;

use App\Filament\Resources\LabOrders\LabOrderResource;
use App\Models\LabOrder;
use App\Models\LabResult;
use App\Models\LabTransfer;
use App\Models\Laboratory;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class LabOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')->label('Order #')->searchable()->weight('semibold')->copyable(),
                TextColumn::make('patient.full_name')->label('Patient')->sortable()->searchable()
                    ->description(fn (LabOrder $record): string => $record->patient->hospital_no ?? ''),
                TextColumn::make('destinationLab.name')->label('Lab')->badge()->color('info')->placeholder('—'),
                TextColumn::make('priority')->label('Priority')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'STAT' => 'danger',
                        'ASAP' => 'warning',
                        'Urgent' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('status')->label('Status')->badge()
                    ->formatStateUsing(fn (string $state): string => LabOrder::STATUSES[$state] ?? $state)
                    ->color(fn (string $state): string => LabOrder::STATUS_COLORS[$state] ?? 'gray'),
                TextColumn::make('items_count')->counts('items')->label('Tests')->badge()->color('info'),
                TextColumn::make('ordered_at')->label('Ordered')->dateTime('d M, H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(LabOrder::STATUSES),
                SelectFilter::make('priority')->options(LabOrder::PRIORITIES),
                SelectFilter::make('destination_lab_id')
                    ->label('Lab')
                    ->relationship('destinationLab', 'name'),
            ])
            ->recordActions([
                Action::make('collectSample')
                    ->label('Collect Sample')
                    ->icon('heroicon-m-beaker')
                    ->color('info')
                    ->button()
                    ->visible(fn (LabOrder $record) => $record->status === 'Pending')
                    ->action(function (LabOrder $record) {
                        $record->update([
                            'status' => 'Sample Collected',
                            'collected_at' => now(),
                            'received_by' => auth()->id(),
                        ]);
                        Notification::make()->success()->title('Sample collected')->send();
                    }),
                Action::make('startAnalysis')
                    ->label('Start Analysis')
                    ->icon('heroicon-m-play')
                    ->color('warning')
                    ->button()
                    ->visible(fn (LabOrder $record) => $record->status === 'Sample Collected')
                    ->action(function (LabOrder $record) {
                        $record->update(['status' => 'In Progress']);
                        $record->items()->where('status', 'Pending')->update(['status' => 'In Progress']);
                        Notification::make()->success()->title('Analysis started')->send();
                    }),
                Action::make('enterResult')
                    ->label('Enter Result')
                    ->icon('heroicon-m-pencil-square')
                    ->color('success')
                    ->button()
                    ->visible(fn (LabOrder $record) => in_array($record->status, ['Sample Collected', 'In Progress']))
                    ->url(fn (LabOrder $record): string => LabOrderResource::getUrl('result-entry', ['record' => $record])),
                Action::make('verify')
                    ->label('Verify & Approve')
                    ->icon('heroicon-m-check-badge')
                    ->color('success')
                    ->button()
                    ->requiresConfirmation()
                    ->modalDescription('Approve all results on this order. Each result will be marked verified with your signature. The order will be locked from further edits.')
                    ->visible(fn (LabOrder $record) => $record->status === 'Completed')
                    ->action(function (LabOrder $record) {
                        DB::transaction(function () use ($record) {
                            foreach ($record->items as $item) {
                                if ($item->result && !$item->result->is_verified) {
                                    $item->result->update([
                                        'is_verified' => true,
                                        'verified_by' => auth()->id(),
                                        'verified_at' => now(),
                                    ]);
                                    $item->update(['status' => 'Verified']);
                                }
                            }
                            $record->update([
                                'status' => 'Verified',
                                'resulted_at' => now(),
                            ]);
                        });
                        Notification::make()->success()->title('Lab order verified and approved')->send();
                    }),
                Action::make('transfer')
                    ->label('Transfer to Lab')
                    ->icon('heroicon-m-arrow-path')
                    ->color('primary')
                    ->button()
                    ->visible(fn (LabOrder $record) => in_array($record->status, ['Pending', 'Sample Collected', 'In Progress']))
                    ->form(function (LabOrder $record) {
                        return [
                            Select::make('to_laboratory_id')
                                ->label('Destination Lab')
                                ->options(
                                    Laboratory::where('id', '!=', $record->destination_lab_id ?? 0)
                                        ->where('is_active', true)
                                        ->pluck('name', 'id')
                                )
                                ->required()
                                ->native(false),
                            TextInput::make('reason')
                                ->label('Reason for Transfer')
                                ->required(),
                            Textarea::make('notes')->rows(2),
                        ];
                    })
                    ->action(function (array $data, LabOrder $record) {
                        DB::transaction(function () use ($data, $record) {
                            LabTransfer::create([
                                'lab_order_id' => $record->id,
                                'from_laboratory_id' => $record->destination_lab_id,
                                'to_laboratory_id' => $data['to_laboratory_id'],
                                'transferred_by' => auth()->id(),
                                'reason' => $data['reason'],
                                'notes' => $data['notes'] ?? null,
                                'status' => 'Pending',
                                'transferred_at' => now(),
                            ]);
                            $record->update(['destination_lab_id' => $data['to_laboratory_id']]);
                        });
                        Notification::make()->success()->title('Order transferred to new lab')->send();
                    }),
                Action::make('printResult')
                    ->label('Print / PDF')
                    ->icon('heroicon-m-printer')
                    ->color('gray')
                    ->button()
                    ->visible(fn (LabOrder $record) => in_array($record->status, ['Completed', 'Verified']))
                    ->url(fn (LabOrder $record): string => route('lab-orders.print', $record))
                    ->openUrlInNewTab(),
                ViewAction::make(),
                EditAction::make(),
            ], position: RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('ordered_at', 'desc');
    }
}
