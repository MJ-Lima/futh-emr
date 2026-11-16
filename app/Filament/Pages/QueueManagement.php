<?php

namespace App\Filament\Pages;

use App\Models\{Appointment, Clinic, Patient, Encounter};
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\DB;

class QueueManagement extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static string|\UnitEnum|null $navigationGroup = 'Queue Management';

    protected static ?string $navigationLabel = 'Queue Management';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.queue-management';

    protected function getViewData(): array
    {
        $today = now()->toDateString();

        // Get today's queue (appointments that are scheduled, checked in, in progress, or completed)
        $queue = Appointment::with(['patient', 'clinic', 'provider'])
            ->whereDate('appointment_date', $today)
            ->whereIn('status', ['Scheduled', 'Checked In', 'In Progress', 'Completed', 'No Show'])
            ->orderBy('appointment_time')
            ->get();

        // KPI Cards
        $kpis = [
            [
                'label' => 'Total in Queue',
                'value' => $queue->count(),
                'icon' => 'heroicon-o-users',
                'color' => 'blue',
                'subtitle' => 'Patients today',
            ],
            [
                'label' => 'Waiting',
                'value' => $queue->where('status', 'Checked In')->count(),
                'icon' => 'heroicon-o-clock',
                'color' => 'orange',
                'subtitle' => 'Avg Wait: 28 mins',
            ],
            [
                'label' => 'In Consultation',
                'value' => $queue->where('status', 'In Progress')->count(),
                'icon' => 'heroicon-o-chat-bubble-left-right',
                'color' => 'purple',
                'subtitle' => 'Today',
            ],
            [
                'label' => 'Completed',
                'value' => $queue->where('status', 'Completed')->count(),
                'icon' => 'heroicon-o-check-circle',
                'color' => 'green',
                'subtitle' => 'Today',
            ],
            [
                'label' => 'Missed',
                'value' => $queue->where('status', 'No Show')->count(),
                'icon' => 'heroicon-o-x-circle',
                'color' => 'red',
                'subtitle' => 'Today',
            ],
        ];

        // Tab counts
        $tabs = [
            'all' => $queue->count(),
            'new' => $queue->where('visit_type', 'New')->count(),
            'returning' => $queue->where('visit_type', 'Follow-up')->count() + $queue->where('visit_type', 'Review')->count(),
        ];

        // Queue Summary (right sidebar)
        $summary = [
            'high_priority' => $queue->where('priority', 'Emergency')->count() + $queue->where('priority', 'Urgent')->count(),
            'checked_in' => $queue->where('status', 'Checked In')->count(),
            'waiting' => $queue->where('status', 'Checked In')->count(),
            'in_consultation' => $queue->where('status', 'In Progress')->count(),
            'completed' => $queue->where('status', 'Completed')->count(),
            'missed' => $queue->where('status', 'No Show')->count(),
        ];

        $avgWaitTime = 25; // placeholder - would calculate from checked_in_at

        return [
            'queue'    => $queue,
            'kpis'     => $kpis,
            'tabs'     => $tabs,
            'summary'  => $summary,
            'avgWaitTime' => $avgWaitTime,
            'clinics'  => Clinic::where('status', 1)->get(),
        ];
    }

    public function getMaxWidth(): MaxWidth|string
    {
        return MaxWidth::Full;
    }
}
