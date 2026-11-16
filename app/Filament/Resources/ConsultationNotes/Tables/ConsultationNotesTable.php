<?php

namespace App\Filament\Resources\ConsultationNotes\Tables;

use App\Models\ConsultationNote;
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

class ConsultationNotesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('note_number')->label('Note #')->searchable()->weight('semibold')->copyable(),
                TextColumn::make('patient.full_name')->label('Patient')->sortable()->searchable()
                    ->description(fn (ConsultationNote $r): string => $r->patient->hospital_no ?? ''),
                TextColumn::make('clinic.name')->label('Clinic')->sortable()->badge()->color('info'),
                TextColumn::make('encounter.encounter_number')->label('Encounter #')->placeholder('—')->badge(),
                TextColumn::make('author.name')->label('Author')->placeholder('—'),
                TextColumn::make('status')->label('Status')->badge()
                    ->formatStateUsing(fn ($s) => ConsultationNote::STATUSES[$s] ?? $s)
                    ->color(fn ($s) => ConsultationNote::STATUS_COLORS[$s] ?? 'gray'),
                TextColumn::make('created_at')->label('Created')->dateTime('d M Y, H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('clinic_id')->label('Clinic')->relationship('clinic', 'name'),
                SelectFilter::make('status')->options(ConsultationNote::STATUSES),
                SelectFilter::make('author')->relationship('author', 'name'),
            ])
            ->recordActions([
                Action::make('print')
                    ->label('Print / PDF')
                    ->icon('heroicon-m-printer')
                    ->color('gray')
                    ->button()
                    ->visible(fn (ConsultationNote $r) => in_array($r->status, ['Finalized', 'Counter-signed', 'Amended']))
                    ->url(fn (ConsultationNote $r): string => route('consultation-notes.print', $r))
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
