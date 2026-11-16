<?php

namespace App\Filament\Pages;

use App\Models\DutyPosting;
use App\Models\Staff;
use App\Models\User;
use Filament\Pages\Page;

class StaffDutyRosterCalendar extends Page
{
    protected string $view = 'filament/pages.staff-duty-roster-calendar';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string|\UnitEnum|null $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Duty Roster Calendar';

    protected static ?string $title = 'Staff Duty Roster Calendar';

    protected static ?int $navigationSort = 25;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        return $user->hasRole(['super_admin', 'medical_administrator', 'nurse']);
    }

    public function getTitle(): string
    {
        return 'Staff Duty Roster Calendar';
    }

    public function getRosterData(): array
    {
        // Get active duty postings for the next 30 days
        $postings = DutyPosting::with(['staff', 'user', 'hospital', 'department', 'clinic'])
            ->where('start_date', '<=', now()->addDays(30))
            ->where('end_date', '>=', now()->subDays(7))
            ->orderBy('start_date')
            ->get();

        // Group by date
        $byDate = [];
        foreach ($postings as $posting) {
            $start = $posting->start_date;
            $end = $posting->end_date;
            for ($date = $start->copy(); $date <= $end; $date->addDay()) {
                $dateStr = $date->format('Y-m-d');
                if (!isset($byDate[$dateStr])) $byDate[$dateStr] = [];
                $byDate[$dateStr][] = [
                    'staff_name' => $posting->staff?->first_name . ' ' . $posting->staff?->last_name ?? $posting->user?->name ?? '—',
                    'shift' => $posting->shift,
                    'role_in_unit' => $posting->role_in_unit,
                    'department' => $posting->department?->name ?? '—',
                    'clinic' => $posting->clinic?->name ?? '—',
                    'hospital' => $posting->hospital?->name ?? '—',
                    'status' => $posting->status,
                ];
            }
        }

        // Summary stats
        $totalPostings = $postings->count();
        $activePostings = $postings->where('status', 'Active')->count();

        // By shift
        $byShift = $postings->groupBy('shift')->map->count()->toArray();

        return [
            'byDate' => $byDate,
            'totalPostings' => $totalPostings,
            'activePostings' => $activePostings,
            'byShift' => $byShift,
        ];
    }
}
