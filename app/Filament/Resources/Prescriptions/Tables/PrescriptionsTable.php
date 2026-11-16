<?php

namespace App\Filament\Resources\Prescriptions\Tables;

use App\Models\DispensingLog;
use App\Models\Drug;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\StockMovement;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class PrescriptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('prescription_number')->label('Rx #')->searchable()->weight('semibold')->copyable(),
                TextColumn::make('patient.full_name')->label('Patient')->sortable()->searchable()
                    ->description(fn (Prescription $record): string => $record->patient->hospital_no ?? ''),
                TextColumn::make('items_count')->counts('items')->label('Items')->badge()->color('info'),
                TextColumn::make('prescriber.name')->label('Prescriber')->placeholder('—'),
                TextColumn::make('priority')->label('Priority')->badge()
                    ->color(fn ($state) => match ($state) {
                        'STAT' => 'danger', 'Urgent' => 'warning', default => 'gray',
                    }),
                TextColumn::make('status')->label('Status')->badge()
                    ->color(fn ($state) => match ($state) {
                        'Dispensed' => 'success',
                        'Partially Dispensed' => 'info',
                        'Pending' => 'warning',
                        'Cancelled' => 'danger', 'Rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('prescribed_at')->label('Prescribed')->dateTime('d M, H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'Pending' => 'Pending',
                    'Partially Dispensed' => 'Partially Dispensed',
                    'Dispensed' => 'Dispensed',
                    'Cancelled' => 'Cancelled',
                ]),
                SelectFilter::make('priority')->options([
                    'Routine' => 'Routine',
                    'Urgent' => 'Urgent',
                    'STAT' => 'STAT',
                ]),
            ])
            ->recordActions([
                Action::make('dispenseAll')
                    ->label('Dispense All')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->button()
                    ->requiresConfirmation()
                    ->modalDescription('Dispense all pending items. This will decrement stock from available batches (FEFO) and create dispensing logs.')
                    ->visible(fn (Prescription $record) => in_array($record->status, ['Pending', 'Partially Dispensed']))
                    ->action(function (Prescription $record) {
                        self::dispenseAll($record);
                    }),
                Action::make('dispensePartial')
                    ->label('Partial Dispense')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->color('info')
                    ->button()
                    ->visible(fn (Prescription $record) => in_array($record->status, ['Pending', 'Partially Dispensed']))
                    ->form(function (Prescription $record) {
                        $fields = [];
                        foreach ($record->items as $item) {
                            if (in_array($item->status, ['Dispensed', 'Cancelled'])) {
                                continue;
                            }
                            $remaining = $item->quantity - $item->quantity_dispensed;
                            $availableStock = $item->drug?->stock_qty ?? 0;
                            $fields[] = TextInput::make("qty_{$item->id}")
                                ->label("{$item->drug_name} (Qty: {$item->quantity}, Remaining: {$remaining}, Stock: {$availableStock})")
                                ->numeric()
                                ->default(min($remaining, $availableStock))
                                ->minValue(0)
                                ->maxValue($remaining);
                        }
                        return $fields;
                    })
                    ->action(function (array $data, Prescription $record) {
                        self::dispensePartial($record, $data);
                    }),
                ViewAction::make(),
                EditAction::make(),
            ], position: RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('prescribed_at', 'desc');
    }

    public static function dispenseAll(Prescription $prescription): void
    {
        DB::transaction(function () use ($prescription) {
            foreach ($prescription->items as $item) {
                if (in_array($item->status, ['Dispensed', 'Cancelled'])) {
                    continue;
                }
                $remaining = (float) $item->quantity - (float) $item->quantity_dispensed;
                if ($remaining <= 0) continue;
                self::dispenseItem($prescription, $item, $remaining);
            }

            $allDispensed = $prescription->items()
                ->whereColumn('quantity', 'quantity_dispensed')
                ->where('status', 'Dispensed')
                ->count() === $prescription->items()->count();

            $prescription->update([
                'status' => $allDispensed ? 'Dispensed' : 'Partially Dispensed',
                'dispensed_by' => auth()->id(),
                'dispensed_at' => $allDispensed ? now() : null,
            ]);

            Notification::make()
                ->success()
                ->title($allDispensed ? 'All items dispensed' : 'Items partially dispensed')
                ->send();
        });
    }

    public static function dispensePartial(Prescription $prescription, array $data): void
    {
        DB::transaction(function () use ($prescription, $data) {
            $dispensedAny = false;
            foreach ($prescription->items as $item) {
                if (in_array($item->status, ['Dispensed', 'Cancelled'])) continue;

                $qty = (float) ($data["qty_{$item->id}"] ?? 0);
                if ($qty <= 0) continue;

                self::dispenseItem($prescription, $item, $qty);
                $dispensedAny = true;
            }

            if ($dispensedAny) {
                $allDispensed = $prescription->items()
                    ->whereColumn('quantity', 'quantity_dispensed')
                    ->where('status', 'Dispensed')
                    ->count() === $prescription->items()->count();

                $prescription->update([
                    'status' => $allDispensed ? 'Dispensed' : 'Partially Dispensed',
                    'dispensed_by' => auth()->id(),
                    'dispensed_at' => $allDispensed ? now() : null,
                ]);

                Notification::make()
                    ->success()
                    ->title($allDispensed ? 'All items dispensed' : 'Items partially dispensed')
                    ->send();
            } else {
                Notification::make()->warning()->title('No items were dispensed')->send();
            }
        });
    }

    protected static function dispenseItem(Prescription $prescription, PrescriptionItem $item, float $quantity): void
    {
        $drug = $item->drug;
        if (!$drug) {
            Notification::make()->danger()->title("Drug not found for item {$item->drug_name}")->send();
            return;
        }

        // FEFO (First-Expiry-First-Out) batch decrement
        $batches = $drug->batches()
            ->where('quantity_remaining', '>', 0)
            ->where('expiry_date', '>', now())
            ->orderBy('expiry_date')
            ->lockForUpdate()
            ->get();

        $remaining = $quantity;
        foreach ($batches as $batch) {
            if ($remaining <= 0) break;
            $take = min($remaining, (float) $batch->quantity_remaining);
            $batch->decrement('quantity_remaining', $take);
            $remaining -= $take;

            StockMovement::create([
                'hospital_id' => $prescription->hospital_id,
                'drug_id' => $drug->id,
                'drug_batch_id' => $batch->id,
                'dispensing_point_id' => $prescription->dispensing_point_id,
                'user_id' => auth()->id(),
                'type' => 'Dispense',
                'reference_number' => $prescription->prescription_number,
                'movement_date' => now(),
                'quantity' => $take,
                'balance_after' => $batch->fresh()->quantity_remaining,
                'reason' => "Dispensed against Rx {$prescription->prescription_number}",
            ]);

            DispensingLog::create([
                'prescription_id' => $prescription->id,
                'prescription_item_id' => $item->id,
                'drug_id' => $drug->id,
                'drug_batch_id' => $batch->id,
                'dispensing_point_id' => $prescription->dispensing_point_id,
                'patient_id' => $prescription->patient_id,
                'user_id' => auth()->id(),
                'quantity_dispensed' => $take,
                'balance_after' => $batch->fresh()->quantity_remaining,
                'dosage_instructions' => trim(($item->dose ?? '') . ' ' . ($item->frequency ?? '') . ' ' . ($item->duration ?? '')),
                'dispensed_at' => now(),
            ]);
        }

        if ($remaining > 0) {
            Notification::make()
                ->warning()
                ->title("Insufficient stock for {$drug->name}")
                ->body("Could only dispense " . ($quantity - $remaining) . " of {$quantity} requested. Short by {$remaining}.")
                ->send();
        }

        $newQtyDispensed = (float) $item->quantity_dispensed + ($quantity - $remaining);
        $newStatus = $newQtyDispensed >= $item->quantity ? 'Dispensed' : 'Partially Dispensed';

        $item->update([
            'quantity_dispensed' => $newQtyDispensed,
            'status' => $newStatus,
        ]);

        // Low-stock alert
        $drug->refresh();
        if ($drug->is_low_stock) {
            Notification::make()
                ->warning()
                ->title("Low stock: {$drug->name}")
                ->body("Current stock ({$drug->stock_qty}) is at or below reorder level ({$drug->reorder_level}).")
                ->persistent()
                ->send();
        }
    }
}
