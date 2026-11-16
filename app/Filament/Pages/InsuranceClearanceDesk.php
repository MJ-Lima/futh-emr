<?php

namespace App\Filament\Pages;

use App\Models\InsuranceClearance;
use Filament\Pages\Page;

class InsuranceClearanceDesk extends Page
{
    protected string $view = 'filament.pages.insurance-clearance-desk';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static string|\UnitEnum|null $navigationGroup = 'Insurance';

    protected static ?string $navigationLabel = 'Clearance Desk';

    protected static ?string $title = 'Insurance Clearance Desk — Approve Services for Insurance Patients';

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        return $user->hasRole(['super_admin', 'insurance_officer', 'medical_administrator']);
    }

    public function getTitle(): string
    {
        return 'Insurance Clearance Desk';
    }

    public function getClearanceData(): array
    {
        $pending = InsuranceClearance::with(['patient', 'policy.provider', 'policy.plan', 'clearedBy'])
            ->where('status', 'Pending')
            ->latest()
            ->limit(30)
            ->get();

        $approved = InsuranceClearance::with(['patient', 'policy.provider', 'clearedBy'])
            ->whereIn('status', ['Approved', 'Partially Approved'])
            ->latest('cleared_at')
            ->limit(20)
            ->get();

        $utilized = InsuranceClearance::with(['patient', 'policy.provider', 'utilizedBy'])
            ->where('status', 'Utilized')
            ->latest('utilized_at')
            ->limit(10)
            ->get();

        $stats = [
            'pending' => InsuranceClearance::where('status', 'Pending')->count(),
            'approved' => InsuranceClearance::whereIn('status', ['Approved', 'Partially Approved'])->count(),
            'utilized' => InsuranceClearance::where('status', 'Utilized')->count(),
            'rejected' => InsuranceClearance::where('status', 'Rejected')->count(),
            'totalEstimated' => InsuranceClearance::where('status', 'Pending')->sum('estimated_cost'),
            'totalApproved' => InsuranceClearance::whereIn('status', ['Approved', 'Partially Approved', 'Utilized'])->sum('approved_amount'),
        ];

        return [
            'pending' => $pending,
            'approved' => $approved,
            'utilized' => $utilized,
            'stats' => $stats,
        ];
    }
}
