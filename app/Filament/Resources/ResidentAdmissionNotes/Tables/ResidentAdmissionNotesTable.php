<?php

namespace App\Filament\Resources\ResidentAdmissionNotes\Tables;

use App\Models\ResidentAdmissionNote;
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

class ResidentAdmissionNotesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('note_number')->label('Note #')->searchable()->weight('semibold')->copyable(),
                TextColumn::make('patient.full_name')->label('Patient')->sortable()->searchable()
                    ->description(fn (ResidentAdmissionNote $r): string => $r->patient->hospital_no ?? ''),
                TextColumn::make('admission.admission_number')->label('Admission #')->placeholder('—')->badge(),
                TextColumn::make('residentDoctor.name')->label('Resident'),
                TextColumn::make('supervisingConsultant.name')->label('Consultant')->placeholder('—'),
                TextColumn::make('status')->label('Status')->badge()
                    ->formatStateUsing(fn ($s) => ResidentAdmissionNote::STATUSES[$s] ?? $s)
                    ->color(fn ($s) => ResidentAdmissionNote::STATUS_COLORS[$s] ?? 'gray'),
                TextColumn::make('created_at')->label('Created')->dateTime('d M Y, H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(ResidentAdmissionNote::STATUSES),
                SelectFilter::make('resident_doctor_id')->label('Resident Doctor')->relationship('residentDoctor', 'name'),
                SelectFilter::make('supervising_consultant_id')->label('Consultant')->relationship('supervisingConsultant', 'name'),
            ])
            ->recordActions([
                Action::make('submit')
                    ->label('Submit to Consultant')
                    ->icon('heroicon-m-paper-airplane')
                    ->color('info')
                    ->button()
                    ->requiresConfirmation()
                    ->visible(fn (ResidentAdmissionNote $r) => $r->status === 'Draft')
                    ->action(function (ResidentAdmissionNote $r) {
                        $r->update(['status' => 'Submitted', 'submitted_at' => now()]);
                        Notification::make()->success()->title('Note submitted to supervising consultant.')->send();
                    }),
                Action::make('countersign')
                    ->label('Counter-sign')
                    ->icon('heroicon-m-check-badge')
                    ->color('success')
                    ->button()
                    ->requiresConfirmation()
                    ->visible(fn (ResidentAdmissionNote $r) => $r->status === 'Submitted')
                    ->action(function (ResidentAdmissionNote $r) {
                        $r->update(['status' => 'Counter-signed', 'countersigned_at' => now()]);
                        Notification::make()->success()->title('Note counter-signed. Resident notified.')->send();
                    }),
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
