<?php

namespace App\Filament\Pages;

use App\Models\DispensingLog;
use Filament\Pages\Page;

class DispensingLogReport extends Page
{
    protected string $view = 'filament.pages.dispensing-log-report';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document';

    protected static string|\UnitEnum|null $navigationGroup = 'Pharmacy';

    protected static ?string $navigationLabel = 'Dispensing Log Report';

    protected static ?string $title = 'Pharmacy Dispensing Log Report';

    protected static ?int $navigationSort = 11;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        return $user->hasRole(['super_admin', 'pharmacist', 'medical_administrator', 'accountant']);
    }

    public function getTitle(): string
    {
        return 'Pharmacy Dispensing Log Report';
    }

    public function getDispensingData(): array
    {
        $logs = DispensingLog::with(['patient', 'drug', 'drugBatch', 'dispensingPoint', 'user', 'prescription'])
            ->latest('dispensed_at')
            ->limit(500)
            ->get();

        // Summary stats
        $totalDispensed = $logs->count();
        $totalQuantity = $logs->sum('quantity_dispensed');
        $uniquePatients = $logs->unique('patient_id')->count();
        $uniqueDrugs = $logs->unique('drug_id')->count();

        // Top dispensed drugs
        $topDrugs = $logs->groupBy('drug.name')
            ->map(fn ($g) => ['count' => $g->count(), 'qty' => $g->sum('quantity_dispensed')])
            ->sortByDesc('count')
            ->take(10)
            ->toArray();

        // Dispensing by point
        $byPoint = $logs->groupBy('dispensingPoint.name')
            ->map(fn ($g) => $g->count())
            ->sortDesc()
            ->toArray();

        return [
            'logs' => $logs,
            'totalDispensed' => $totalDispensed,
            'totalQuantity' => $totalQuantity,
            'uniquePatients' => $uniquePatients,
            'uniqueDrugs' => $uniqueDrugs,
            'topDrugs' => $topDrugs,
            'byPoint' => $byPoint,
        ];
    }
}
