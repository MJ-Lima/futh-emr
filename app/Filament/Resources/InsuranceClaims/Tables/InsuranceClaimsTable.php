<?php

namespace App\Filament\Resources\InsuranceClaims\Tables;

use App\Models\InsuranceClaim;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InsuranceClaimsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('claim_number')->label('Claim #')->searchable()->weight('semibold')->copyable(),
                    TextColumn::make('patient.full_name')->label('Patient')->sortable()->searchable(),
                    TextColumn::make('provider.name')->label('Provider')->sortable(),
                    TextColumn::make('claim_type')->label('Type')->badge()->color('info'),
                    TextColumn::make('service_date_start')->label('Service From')->date()->sortable(),
                    TextColumn::make('total_claimed')->label('Claimed')->money('NGN')->sortable(),
                    TextColumn::make('total_approved')->label('Approved')->money('NGN')->sortable(),
                    TextColumn::make('total_paid')->label('Paid')->money('NGN')->sortable(),
                    TextColumn::make('status')->label('Status')->badge()
                        ->color(fn ($s) => match($s) {
                            'Draft' => 'gray',
                            'Submitted' => 'info',
                            'Acknowledged' => 'info',
                            'Processing' => 'warning',
                            'Approved' => 'success',
                            'Partially Approved' => 'warning',
                            'Rejected' => 'danger',
                            'Paid' => 'success',
                            'Reversed' => 'danger',
                            default => 'gray',
                        }),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'Draft' => 'Draft',
                    'Submitted' => 'Submitted',
                    'Acknowledged' => 'Acknowledged',
                    'Processing' => 'Processing',
                    'Approved' => 'Approved',
                    'Partially Approved' => 'Partially Approved',
                    'Rejected' => 'Rejected',
                    'Paid' => 'Paid',
                    'Reversed' => 'Reversed',
                ]),
                SelectFilter::make('claim_type')->options([
                    'Outpatient' => 'Outpatient',
                    'Inpatient' => 'Inpatient',
                    'Emergency' => 'Emergency',
                    'Pharmacy' => 'Pharmacy',
                    'Laboratory' => 'Laboratory',
                    'Radiology' => 'Radiology',
                    'Procedure' => 'Procedure',
                    'Maternity' => 'Maternity',
                ]),
                SelectFilter::make('insurance_provider_id')->label('Provider')->relationship('insuranceProvider', 'name'),
            ])
            ->recordActions([
                Action::make('print')
                    ->label('Print / PDF')
                    ->icon('heroicon-m-printer')
                    ->color('gray')
                    ->button()
                    ->visible(fn (InsuranceClaim $r) => in_array($r->status, ['Submitted', 'Acknowledged', 'Processing', 'Approved', 'Partially Approved', 'Paid']))
                    ->url(fn (InsuranceClaim $r): string => route('insurance-claims.print', $r))
                    ->openUrlInNewTab(),
                ViewAction::make(),
                EditAction::make(),
            ], position: RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
