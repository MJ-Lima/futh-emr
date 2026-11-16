<?php

namespace App\Filament\Resources\MedicalCertificates\Tables;

use App\Models\MedicalCertificate;
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

class MedicalCertificatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('certificate_number')->label('Certificate #')->searchable()->weight('semibold')->copyable(),
                TextColumn::make('patient.full_name')->label('Patient')->sortable()->searchable()
                    ->description(fn (MedicalCertificate $r): string => $r->patient->hospital_no ?? ''),
                TextColumn::make('certificate_type')->label('Type')->badge()->color('info'),
                TextColumn::make('patient_category')->label('Category')->badge()
                    ->color(fn ($s) => match ($s) {
                        'Inpatient' => 'warning',
                        'Emergency' => 'danger',
                        default     => 'success',
                    }),
                TextColumn::make('issued_date')->label('Issued')->date('d M Y')->sortable(),
                TextColumn::make('valid_until')->label('Valid Until')->date('d M Y')->placeholder('—'),
                TextColumn::make('status')->label('Status')->badge()
                    ->formatStateUsing(fn ($s) => MedicalCertificate::STATUSES[$s] ?? $s)
                    ->color(fn ($s) => MedicalCertificate::STATUS_COLORS[$s] ?? 'gray'),
                TextColumn::make('issuer.name')->label('Issued By')->placeholder('—')->toggleable(),
            ])
            ->filters([
                SelectFilter::make('certificate_type')->options(MedicalCertificate::TYPES),
                SelectFilter::make('patient_category')->options(MedicalCertificate::CATEGORIES),
                SelectFilter::make('status')->options(MedicalCertificate::STATUSES),
            ])
            ->recordActions([
                Action::make('issue')
                    ->label('Issue Certificate')
                    ->icon('heroicon-m-check-badge')
                    ->color('success')
                    ->button()
                    ->requiresConfirmation()
                    ->visible(fn (MedicalCertificate $r) => $r->status === 'Draft')
                    ->action(function (MedicalCertificate $r) {
                        $r->update(['status' => 'Issued']);
                        Notification::make()->success()->title('Certificate issued')->send();
                    }),
                Action::make('printCertificate')
                    ->label('Print / PDF')
                    ->icon('heroicon-m-printer')
                    ->color('gray')
                    ->button()
                    ->visible(fn (MedicalCertificate $r) => in_array($r->status, ['Issued', 'Printed']))
                    ->url(fn (MedicalCertificate $r): string => route('medical-certificates.print', $r))
                    ->openUrlInNewTab(),
                ViewAction::make(),
                EditAction::make(),
            ], position: RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('issued_date', 'desc');
    }
}
