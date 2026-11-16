<?php

namespace App\Filament\Widgets;

use App\Models\{Encounter, LabOrder, Patient, Prescription, InsuranceClaim, Payment, Appointment, Bed, Admission};
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FuthStatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = -3;

    protected int | string | array $columnSpan = 'full';

    public function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        $today = now()->toDateString();

        // Get chart data for last 7 days for patient encounters
        $encountersChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $encountersChart[] = Encounter::whereDate('encounter_date', $date)->count();
        }

        return [
            Stat::make('Total Patients', Patient::count())
                ->description('Registered in system')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success')
                ->chart($encountersChart)
                ->icon('heroicon-o-users'),

            Stat::make('Today\'s Encounters', Encounter::whereDate('encounter_date', $today)->count())
                ->description('Patient visits today')
                ->descriptionIcon('heroicon-o-clipboard-document-check')
                ->color('info')
                ->icon('heroicon-o-clipboard-document-check'),

            Stat::make('Pending Labs', LabOrder::where('status', 'Pending')->count())
                ->description('Awaiting results')
                ->descriptionIcon('heroicon-o-beaker')
                ->color('warning')
                ->icon('heroicon-o-beaker'),

            Stat::make('Revenue Today', 'NGN ' . number_format((float) Payment::whereDate('payment_date', $today)->sum('amount'), 0))
                ->description('Cash & electronic')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success')
                ->icon('heroicon-o-banknotes'),

            Stat::make('Appointments Today', Appointment::whereDate('appointment_date', $today)->count())
                ->description('Scheduled visits')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('info')
                ->icon('heroicon-o-calendar-days'),

            Stat::make('Pending Prescriptions', Prescription::where('status', 'Pending')->count())
                ->description('Awaiting dispensing')
                ->descriptionIcon('heroicon-o-clipboard-document')
                ->color('warning')
                ->icon('heroicon-o-clipboard-document'),

            Stat::make('Open Claims', InsuranceClaim::whereNotIn('status', ['Paid', 'Rejected', 'Reversed'])->count())
                ->description('Insurance in progress')
                ->descriptionIcon('heroicon-o-shield-check')
                ->color('info')
                ->icon('heroicon-o-shield-check'),

            Stat::make('Beds Occupied', Admission::whereNull('discharge_date')->count() . ' / ' . Bed::count())
                ->description('Current admissions')
                ->descriptionIcon('heroicon-o-building-office-2')
                ->color('success')
                ->icon('heroicon-o-building-office-2'),
        ];
    }
}
