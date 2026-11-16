<?php

namespace App\Filament\Widgets;

use App\Models\Patient;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class RecentPatientsWidget extends BaseWidget
{
    protected static ?int $sort = -2;
    protected int | string | array $columnSpan = 'full';

    protected function getTableHeading(): string
    {
        return 'Recent Patients';
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'created_at';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    public function getDefaultTableRecordsPerPage(): int
    {
        return 5;
    }

    protected function getTableQuery(): Builder
    {
        return Patient::query()->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('hospital_no')
                ->label('Hospital No')
                ->searchable()
                ->weight('medium'),
            TextColumn::make('surname')
                ->label('Name')
                ->searchable()
                ->formatStateUsing(fn ($record) => ($record->first_name ?? '') . ' ' . ($record->surname ?? ''))
                ->weight('medium'),
            TextColumn::make('gender')
                ->label('Gender')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'Male' => 'info',
                    'Female' => 'danger',
                    default => 'gray',
                }),
            TextColumn::make('phone')
                ->label('Phone')
                ->searchable(),
            TextColumn::make('created_at')
                ->label('Registered')
                ->dateTime('M d, Y')
                ->since(),
        ];
    }
}
