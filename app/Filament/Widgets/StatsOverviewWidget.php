<?php

namespace App\Filament\Widgets;

use App\Models\{Encounter, LabOrder, Patient, Prescription, InsuranceClaim, Payment};
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = -3;

    protected function getStats(): array
    {
        $today = now()->toDateString();

        return [
            Stat::make('Patients Total', Patient::count())
                ->description('Registered in system')
                ->color('success')
                ->chart([7, 12, 9, 14, 18, 22, 28])
                ->icon('heroicon-o-users'),

            Stat::make('Encounters Today', Encounter::whereDate('encounter_date', $today)->count())
                ->description('Patient visits today')
                ->color('info')
                ->icon('heroicon-o-clipboard-document-check'),

            Stat::make('Pending Lab Orders', LabOrder::where('status', 'Pending')->count())
                ->description('Awaiting sample collection')
                ->color('warning')
                ->icon('heroicon-o-beaker'),

            Stat::make('Pending Prescriptions', Prescription::where('status', 'Pending')->count())
                ->description('Awaiting dispensing')
                ->color('success')
                ->icon('heroicon-o-clipboard-document'),

            Stat::make('Open Insurance Claims', InsuranceClaim::whereNotIn('status', ['Paid', 'Rejected', 'Reversed'])->count())
                ->description('In submission / processing')
                ->color('info')
                ->icon('heroicon-o-shield-check'),

            Stat::make('Revenue Today', 'NGN ' . number_format((float) Payment::whereDate('payment_date', $today)->sum('amount'), 2))
                ->description('Cash & electronic collections')
                ->color('success')
                ->icon('heroicon-o-banknotes'),
        ];
    }
}
