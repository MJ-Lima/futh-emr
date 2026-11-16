<?php

namespace App\Filament\Resources\CashierTasks\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CashierTaskInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Cashier Task')
                ->schema([
                    TextEntry::make('task_number')->badge()->color('gray'),
                    TextEntry::make('status')->badge()->color(fn ($state) => match ($state) {
                        'pending'       => 'warning',
                        'in_progress'   => 'info',
                        'paid'          => 'success',
                        'short_paid'    => 'info',
                        'cancelled'     => 'danger',
                        'refunded'      => 'gray',
                        default          => 'gray',
                    }),
                    TextEntry::make('source_type')->badge(),
                    TextEntry::make('created_at')->dateTime(),
                ])->columns(4),

            Section::make('What is being billed')
                ->schema([
                    TextEntry::make('description'),
                    TextEntry::make('amount_due')->money('NGN'),
                    TextEntry::make('amount_paid')->money('NGN'),
                    TextEntry::make('payment_method'),
                    TextEntry::make('receipt_number'),
                    TextEntry::make('paid_at')->dateTime(),
                ])->columns(3),

            Section::make('Patient')
                ->schema([
                    TextEntry::make('patient.full_name'),
                    TextEntry::make('patient.hospital_no')->label('Hospital No'),
                    TextEntry::make('patient.hospital.name'),
                    TextEntry::make('encounter.encounter_number')->label('Encounter'),
                ])->columns(4),

            Section::make('Cashier')
                ->schema([
                    TextEntry::make('assignedCashier.name')->placeholder('—'),
                    TextEntry::make('assigned_at')->dateTime(),
                    TextEntry::make('notes')->columnSpanFull(),
                ])->columns(3),
        ]);
    }
}
