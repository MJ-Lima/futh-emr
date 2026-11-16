<?php

namespace App\Filament\Resources\Referrals\Tables;

use App\Models\Referral;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReferralsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('referral_number')->label('Referral #')->searchable()->weight('semibold')->copyable(),
                TextColumn::make('patient.full_name')->label('Patient')->sortable()->searchable()
                    ->description(fn (Referral $r): string => $r->patient->hospital_no ?? ''),
                TextColumn::make('referral_type')->label('Type')->badge()->color('info'),
                TextColumn::make('patient_category')->label('Category')->badge()
                    ->color(fn ($s) => match ($s) {
                        'Inpatient' => 'warning',
                        'Emergency'  => 'danger',
                        default      => 'success',
                    }),
                TextColumn::make('destination_facility')->label('Destination')
                    ->placeholder(fn ($r) => $r->referredTo?->name ?? '—')
                    ->limit(30),
                TextColumn::make('urgency')->label('Urgency')->badge()
                    ->color(fn ($s) => Referral::URGENCY_COLORS[$s] ?? 'gray'),
                TextColumn::make('status')->label('Status')->badge()
                    ->formatStateUsing(fn ($s) => Referral::STATUSES[$s] ?? $s)
                    ->color(fn ($s) => Referral::STATUS_COLORS[$s] ?? 'gray'),
                TextColumn::make('referred_date')->label('Referred')->date('d M Y')->sortable(),
            ])
            ->filters([
                SelectFilter::make('referral_type')->options(Referral::TYPES),
                SelectFilter::make('patient_category')->options(Referral::CATEGORIES),
                SelectFilter::make('urgency')->options(Referral::URGENCIES),
                SelectFilter::make('status')->options(Referral::STATUSES),
            ])
            ->recordActions([
                Action::make('accept')
                    ->label('Accept Referral')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->button()
                    ->requiresConfirmation()
                    ->visible(fn (Referral $r) => $r->status === 'Pending')
                    ->action(function (Referral $r) {
                        $r->update([
                            'status' => 'Accepted',
                            'received_by' => auth()->id(),
                            'received_at' => now(),
                        ]);
                        Notification::make()->success()->title('Referral accepted')->send();
                    }),
                Action::make('complete')
                    ->label('Mark Completed')
                    ->icon('heroicon-m-check-badge')
                    ->color('info')
                    ->button()
                    ->requiresConfirmation()
                    ->visible(fn (Referral $r) => $r->status === 'Accepted')
                    ->action(function (Referral $r) {
                        $r->update(['status' => 'Completed']);
                        Notification::make()->success()->title('Referral marked completed')->send();
                    }),
                Action::make('printReferral')
                    ->label('Print / PDF')
                    ->icon('heroicon-m-printer')
                    ->color('gray')
                    ->button()
                    ->url(fn (Referral $r): string => route('referrals.print', $r))
                    ->openUrlInNewTab(),
                ViewAction::make(),
                EditAction::make(),
            ], position: RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('referred_date', 'desc');
    }
}
