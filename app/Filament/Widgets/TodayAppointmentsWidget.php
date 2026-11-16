<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;

class TodayAppointmentsWidget extends BaseWidget
{
    protected static ?int $sort = -1;
    protected int | string | array $columnSpan = 'full';

    protected function getTableHeading(): string
    {
        return "Today's Appointments (" . now()->format('M d, Y') . ")";
    }

    public function getDefaultTableRecordsPerPage(): int
    {
        return 5;
    }

    protected function getTableQuery(): Builder
    {
        return Appointment::query()
            ->whereDate('appointment_date', now()->toDateString())
            ->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('patient.hospital_no')
                ->label('Patient ID')
                ->searchable()
                ->weight('medium'),
            TextColumn::make('patient.surname')
                ->label('Patient')
                ->searchable()
                ->formatStateUsing(fn ($record) => ($record->patient?->first_name ?? '') . ' ' . ($record->patient?->surname ?? 'N/A'))
                ->weight('medium'),
            TextColumn::make('appointment_time')
                ->label('Time')
                ->time('h:i A'),
            TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->color(fn (string $state): string => match (strtolower($state)) {
                    'scheduled', 'booked' => 'info',
                    'checked-in', 'arrived' => 'warning',
                    'in-consultation', 'in progress' => 'success',
                    'completed', 'seen' => 'gray',
                    'cancelled', 'no-show', 'missed' => 'danger',
                    default => 'gray',
                }),
            TextColumn::make('clinic.name')
                ->label('Clinic')
                ->default('N/A'),
        ];
    }
}
