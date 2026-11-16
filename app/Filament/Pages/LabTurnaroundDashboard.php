<?php

namespace App\Filament\Pages;

use App\Models\LabOrder;
use App\Models\LabOrderItem;
use Filament\Pages\Page;

class LabTurnaroundDashboard extends Page
{
    protected string $view = 'filament.pages.lab-turnaround-dashboard';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static string|\UnitEnum|null $navigationGroup = 'Laboratory';

    protected static ?string $navigationLabel = 'Turnaround Time Dashboard';

    protected static ?string $title = 'Laboratory Turnaround Time Dashboard';

    protected static ?int $navigationSort = 10;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        return $user->hasRole(['super_admin', 'laboratory_scientist', 'medical_administrator']);
    }

    public function getTitle(): string
    {
        return 'Laboratory Turnaround Time Dashboard';
    }

    public function getTurnaroundData(): array
    {
        // Get all lab orders with their items and results
        $orders = LabOrder::with(['items.test', 'items.result', 'patient', 'destinationLab', 'requestedBy'])
            ->whereNotNull('ordered_at')
            ->latest()
            ->limit(200)
            ->get();

        $stats = [
            'totalOrders' => $orders->count(),
            'pending' => $orders->whereIn('status', ['Pending', 'Sample Collected'])->count(),
            'inProgress' => $orders->where('status', 'In Progress')->count(),
            'completed' => $orders->where('status', 'Completed')->count(),
            'verified' => $orders->where('status', 'Verified')->count(),
            'cancelled' => $orders->whereIn('status', ['Cancelled', 'Rejected'])->count(),
        ];

        // Calculate turnaround times (in hours) for completed/verified orders
        $tatData = [];
        $tatByLab = [];
        $tatByPriority = [];

        foreach ($orders as $order) {
            if (in_array($order->status, ['Completed', 'Verified']) && $order->ordered_at && $order->resulted_at) {
                $tatHours = $order->ordered_at->diffInHours($order->resulted_at);
                $tatData[] = $tatHours;

                $labName = $order->destinationLab?->name ?? 'Unassigned';
                if (!isset($tatByLab[$labName])) $tatByLab[$labName] = [];
                $tatByLab[$labName][] = $tatHours;

                $priority = $order->priority ?? 'Routine';
                if (!isset($tatByPriority[$priority])) $tatByPriority[$priority] = [];
                $tatByPriority[$priority][] = $tatHours;
            }
        }

        // Calculate averages
        $avgTAT = count($tatData) > 0 ? round(array_sum($tatData) / count($tatData), 1) : 0;
        $minTAT = count($tatData) > 0 ? min($tatData) : 0;
        $maxTAT = count($tatData) > 0 ? max($tatData) : 0;

        $tatByLabSummary = [];
        foreach ($tatByLab as $lab => $times) {
            $tatByLabSummary[$lab] = [
                'count' => count($times),
                'avg' => round(array_sum($times) / count($times), 1),
                'min' => min($times),
                'max' => max($times),
            ];
        }

        $tatByPrioritySummary = [];
        foreach ($tatByPriority as $priority => $times) {
            $tatByPrioritySummary[$priority] = [
                'count' => count($times),
                'avg' => round(array_sum($times) / count($times), 1),
            ];
        }

        // Recent orders with TAT
        $recentOrders = $orders->take(20)->map(function ($order) {
            $tat = ($order->ordered_at && $order->resulted_at) ? $order->ordered_at->diffInHours($order->resulted_at) : null;
            return [
                'order_number' => $order->order_number,
                'patient' => $order->patient?->full_name ?? '—',
                'lab' => $order->destinationLab?->name ?? '—',
                'priority' => $order->priority,
                'ordered_at' => $order->ordered_at?->format('d M, H:i'),
                'resulted_at' => $order->resulted_at?->format('d M, H:i'),
                'tat_hours' => $tat,
                'status' => $order->status,
                'tests_count' => $order->items->count(),
            ];
        })->toArray();

        return [
            'stats' => $stats,
            'avgTAT' => $avgTAT,
            'minTAT' => $minTAT,
            'maxTAT' => $maxTAT,
            'tatByLab' => $tatByLabSummary,
            'tatByPriority' => $tatByPrioritySummary,
            'recentOrders' => $recentOrders,
        ];
    }
}
