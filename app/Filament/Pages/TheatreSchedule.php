<?php

namespace App\Filament\Pages;

use App\Models\ProcedureBooking;
use App\Models\Theatre;
use Filament\Pages\Page;

class TheatreSchedule extends Page
{
    protected string $view = 'filament.pages.theatre-schedule';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string|\UnitEnum|null $navigationGroup = 'Inpatient';

    protected static ?string $navigationLabel = 'Theatre Schedule';

    protected static ?string $title = 'Theatre / Operation Scheduling';

    protected static ?int $navigationSort = 8;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        return $user->hasRole(['super_admin', 'doctor', 'nurse', 'medical_administrator']);
    }

    public function getTitle(): string
    {
        return 'Theatre / Operation Scheduling';
    }

    public function getScheduleData(): array
    {
        // Get all active theatres
        $theatres = Theatre::where('is_active', true)->orderBy('code')->get();

        // Get bookings for the next 7 days (and today)
        $startDate = now()->startOfDay();
        $endDate = now()->addDays(7)->endOfDay();

        $bookings = ProcedureBooking::with(['patient', 'procedure', 'surgeon', 'anaesthetist', 'theatre'])
            ->whereBetween('scheduled_start', [$startDate, $endDate])
            ->whereIn('booking_status', ['Scheduled', 'Confirmed', 'In Progress', 'Completed'])
            ->orderBy('scheduled_start')
            ->get();

        // Group bookings by theatre and date
        $schedule = [];
        foreach ($theatres as $theatre) {
            $theatreBookings = $bookings->where('theatre_id', $theatre->id);
            $daySchedule = [];
            for ($i = 0; $i < 7; $i++) {
                $date = now()->addDays($i)->format('Y-m-d');
                $dayBookings = $theatreBookings->filter(function ($b) use ($date) {
                    return $b->scheduled_start?->format('Y-m-d') === $date;
                })->values();
                $daySchedule[$date] = $dayBookings->map(fn ($b) => [
                    'booking_number' => $b->booking_number,
                    'patient' => $b->patient?->full_name ?? '—',
                    'procedure' => $b->procedure?->name ?? '—',
                    'surgeon' => $b->surgeon?->name ?? '—',
                    'anaesthetist' => $b->anaesthetist?->name ?? '—',
                    'start' => $b->scheduled_start?->format('H:i'),
                    'end' => $b->scheduled_end?->format('H:i'),
                    'priority' => $b->priority,
                    'status' => $b->booking_status,
                    'consent_signed' => $b->consent_signed,
                    'fasting_confirmed' => $b->fasting_confirmed,
                ])->toArray();
            }
            $schedule[] = [
                'theatre' => $theatre,
                'days' => $daySchedule,
                'today_count' => $theatre->today_bookings_count,
                'in_use' => $theatre->is_in_use,
            ];
        }

        // Stats
        $stats = [
            'totalTheatres' => $theatres->count(),
            'totalBookings' => $bookings->count(),
            'scheduled' => $bookings->where('booking_status', 'Scheduled')->count(),
            'confirmed' => $bookings->where('booking_status', 'Confirmed')->count(),
            'inProgress' => $bookings->where('booking_status', 'In Progress')->count(),
            'completed' => $bookings->where('booking_status', 'Completed')->count(),
            'elective' => $bookings->where('priority', 'Elective')->count(),
            'urgent' => $bookings->where('priority', 'Urgent')->count(),
            'emergency' => $bookings->where('priority', 'Emergency')->count(),
        ];

        // Day headers
        $dayHeaders = [];
        for ($i = 0; $i < 7; $i++) {
            $dayHeaders[] = [
                'date' => now()->addDays($i)->format('Y-m-d'),
                'label' => now()->addDays($i)->format('D d M'),
                'isToday' => $i === 0,
            ];
        }

        return [
            'theatres' => $schedule,
            'stats' => $stats,
            'dayHeaders' => $dayHeaders,
        ];
    }
}
