<?php

namespace App\Filament\Resources\WardRoundNotes\Tables;

use App\Models\WardRoundNote;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WardRoundNotesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('note_number')->label('Note #')->searchable()->weight('semibold')->copyable(),
                TextColumn::make('patient.full_name')->label('Patient')->sortable()->searchable()
                    ->description(fn (WardRoundNote $r): string => $r->patient->hospital_no ?? ''),
                TextColumn::make('ward.name')->label('Ward')->placeholder('—')->sortable(),
                TextColumn::make('round_date')->label('Round Date')->date('d M Y')->sortable(),
                TextColumn::make('round_time')->label('Time')->time('H:i'),
                TextColumn::make('round_type')->label('Type')->badge(),
                TextColumn::make('doctor.name')->label('Doctor'),
                TextColumn::make('assessment')->label('Assessment')->limit(40)->wrap()->toggleable(),
                TextColumn::make('created_at')->label('Created')->dateTime('d M, H:i')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('round_type')->options(WardRoundNote::ROUND_TYPES),
                SelectFilter::make('ward_id')->label('Ward')->relationship('ward', 'name'),
                SelectFilter::make('doctor_id')->label('Doctor')->relationship('doctor', 'name'),
                Filter::make('round_date')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')->label('Round date from'),
                        \Filament\Forms\Components\DatePicker::make('to')->label('Round date to'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($q) => $q->whereDate('round_date', '>=', $data['from']))
                            ->when($data['to'], fn ($q) => $q->whereDate('round_date', '<=', $data['to']));
                    }),
            ])
            ->recordActions([
                Action::make('print')
                    ->label('Print / PDF')
                    ->icon('heroicon-m-printer')
                    ->color('gray')
                    ->button()
                    ->url(fn ($r): string => route('ward-round-notes.print', $r))
                    ->openUrlInNewTab(),
                ViewAction::make(),
                EditAction::make(),
            ], position: RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('round_date', 'desc');
    }
}
