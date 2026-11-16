<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class ReportsDashboard extends Page
{
    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-chart-bar';
    }

    protected string $view = 'filament.pages.reports-dashboard';
    protected static ?int $navigationSort = 100;

    private function sc(string $table, ?callable $q = null): int
    {
        try {
            $r = DB::table($table);
            if ($q) $r = $q($r);
            return $r->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    private function safeRows(string $sql, array $bindings = []): array
    {
        try {
            return array_map(fn($row) => (array) $row, DB::select($sql, $bindings));
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function getViewData(): array
    {
        return [
            $patientStats = $this->patientStats(),
            'patientStats' => $patientStats,
            $encounterStats = $this->encounterStats(),
            'encounterStats' => $encounterStats,
            'departmentStats' => $this->departmentList(),
            'userStats' => $this->userList(),
            'bedStats' => $this->bedStats(),
            'wardStats' => $this->wardStats(),
            'pharmacyStats' => $this->pharmacyStats(),
            'labStats' => $this->labStats(),
            'radiologyStats' => $this->radiologyStats(),
            'financialStats' => $this->financialStats(),
            'insuranceStats' => $this->insuranceStats(),
            'bloodBankStats' => $this->bloodBankStats(),
            'appointmentStats' => $this->appointmentStats(),
            'hrStats' => $this->hrStats(),
            'auditStats' => $this->auditStats(),
            $monthlyTrends = $this->monthlyTrends(),
            'monthlyTrends' => $monthlyTrends,
            'genderChartData' => [$patientStats['male'], $patientStats['female']],
            'encounterChartData' => [$encounterStats['outpatient'], $encounterStats['inpatient'], $encounterStats['emergency'], $encounterStats['telemedicine']],
            'trendLabels' => array_column($monthlyTrends, 'month'),
            'trendPatients' => array_column($monthlyTrends, 'patients'),
            'trendEncounters' => array_column($monthlyTrends, 'encounters'),
        ];
    }

    private function patientStats(): array
    {
        return [
            'total' => $this->sc('patients'),
            'male' => $this->sc('patients', fn($q) => $q->where('gender', 'male')),
            'female' => $this->sc('patients', fn($q) => $q->where('gender', 'female')),
            'new_this_month' => $this->sc('patients', fn($q) => $q->whereMonth('created_at', now()->month)),
            'new_this_year' => $this->sc('patients', fn($q) => $q->whereYear('created_at', now()->year)),
            'verified' => 0,
            'pending_verification' => 0,
        ];
    }

    private function encounterStats(): array
    {
        return [
            'total' => $this->sc('encounters'),
            'today' => $this->sc('encounters', fn($q) => $q->whereDate('encounter_date', today())),
            'outpatient' => $this->sc('encounters', fn($q) => $q->where('visit_type', 'Outpatient')),
            'inpatient' => $this->sc('encounters', fn($q) => $q->where('visit_type', 'Inpatient')),
            'emergency' => $this->sc('encounters', fn($q) => $q->where('visit_type', 'Emergency')),
            'telemedicine' => $this->sc('encounters', fn($q) => $q->where('visit_type', 'Telemedicine')),
        ];
    }

    private function departmentList(): array
    {
        return $this->safeRows(
            'SELECT d.name, d.code, (SELECT COUNT(*) FROM users u WHERE u.department_id = d.id) as staff_count FROM departments d ORDER BY d.name'
        );
    }

    private function userList(): array
    {
        return $this->safeRows(
            'SELECT name, email, COALESCE(role, "Staff") as role, last_login FROM users ORDER BY name LIMIT 50'
        );
    }

    private function userStatsSingle(): array
    {
        return [
            'total' => $this->sc('users'),
            'active' => $this->sc('users', fn($q) => $q->where('is_active', 1)),
            'doctors' => $this->sc('users', fn($q) => $q->where('role', 'doctor')),
            'nurses' => $this->sc('users', fn($q) => $q->where('role', 'nurse')),
            'on_leave' => 0,
        ];
    }

    private function bedStats(): array
    {
        return [
            'total' => $this->sc('beds'),
            'occupied' => $this->sc('beds', fn($q) => $q->where('status', 'occupied')),
            'available' => $this->sc('beds', fn($q) => $q->where('status', 'available')),
            'maintenance' => $this->sc('beds', fn($q) => $q->where('status', 'maintenance')),
        ];
    }

    private function wardStats(): array
    {
        $total = $this->sc('beds');
        $occupied = $this->sc('beds', fn($q) => $q->where('status', 'occupied'));
        return [
            'beds' => $total,
            'beds_occupied' => $occupied,
            'beds_available' => $this->sc('beds', fn($q) => $q->where('status', 'available')),
            'occupancy_pct' => $total > 0 ? round(($occupied / $total) * 100) : 0,
        ];
    }

    private function pharmacyStats(): array
    {
        return [
            'total_drugs' => $this->sc('inventory_items'),
            'low_stock' => $this->sc('inventory_items', fn($q) => $q->where('quantity', '<=', 10)),
            'prescriptions' => $this->sc('prescriptions'),
            'pending_dispensing' => $this->sc('prescriptions', fn($q) => $q->where('status', 'pending')),
            'dispensed' => $this->sc('prescriptions', fn($q) => $q->where('status', 'dispensed')),
        ];
    }

    private function labStats(): array
    {
        return [
            'total_orders' => $this->sc('lab_orders'),
            'pending' => $this->sc('lab_orders', fn($q) => $q->where('status', 'pending')),
            'completed' => $this->sc('lab_orders', fn($q) => $q->where('status', 'completed')),
        ];
    }

    private function radiologyStats(): array
    {
        return [
            'total' => $this->sc('radiology_orders'),
            'pending' => $this->sc('radiology_orders', fn($q) => $q->where('status', 'pending')),
            'completed' => $this->sc('radiology_orders', fn($q) => $q->where('status', 'completed')),
        ];
    }

    private function financialStats(): array
    {
        return [
            'total_revenue' => 0, 'total_expenses' => 0, 'pending_claims' => 0,
            'total_assets' => 0, 'total_liabilities' => 0, 'net_income' => 0,
            'revenue_year' => 0, 'revenue_today' => 0, 'revenue_month' => 0,
            'expenses_month' => 0, 'outstanding' => 0, 'collections_today' => 0,
            'collections_month' => 0,
        ];
    }

    private function insuranceStats(): array
    {
        return [
            'providers' => $this->sc('insurance_providers'),
            'plans' => $this->sc('insurance_plans'),
            'active_policies' => $this->sc('patient_insurances', fn($q) => $q->where('status', 'active')),
            'pending_pre_auth' => $this->sc('pre_authorizations', fn($q) => $q->where('status', 'pending')),
            'pending_claims' => 0, 'paid_claims' => 0, 'total_claims' => 0, 'rejected_claims' => 0,
        ];
    }

    private function bloodBankStats(): array
    {
        return [
            'donors' => $this->sc('blood_donors'),
            'pending_requests' => $this->sc('blood_bank_requests', fn($q) => $q->where('status', 'pending')),
            'units_available' => $this->sc('blood_units', fn($q) => $q->where('status', 'available')),
            'expired' => $this->sc('blood_units', fn($q) => $q->where('status', 'expired')),
        ];
    }

    private function appointmentStats(): array
    {
        return [
            'total' => $this->sc('appointments'),
            'today' => $this->sc('appointments', fn($q) => $q->whereDate('appointment_date', today())),
            'pending' => $this->sc('appointments', fn($q) => $q->where('status', 'pending')),
            'completed' => $this->sc('appointments', fn($q) => $q->where('status', 'completed')),
            'cancelled' => $this->sc('appointments', fn($q) => $q->where('status', 'cancelled')),
            'no_show' => $this->sc('appointments', fn($q) => $q->where('status', 'no_show')),
        ];
    }

    private function hrStats(): array
    {
        return [
            'staff' => $this->sc('users'),
            'on_leave' => 0,
        ];
    }

    private function auditStats(): array
    {
        return [
            'medical_audits' => 0, 'internal_audits' => 0,
            'open_findings' => 0, 'critical_findings' => 0,
        ];
    }

    private function monthlyTrends(): array
    {
        $pc = [];
        $ec = [];
        $year = now()->year;
        try {
            $pc = DB::table('patients')
                ->selectRaw('MONTH(created_at) as m, COUNT(*) as c')
                ->whereYear('created_at', $year)->groupBy('m')->pluck('c', 'm')->toArray();
        } catch (\Throwable $e) {}
        try {
            $ec = DB::table('encounters')
                ->selectRaw('MONTH(encounter_date) as m, COUNT(*) as c')
                ->whereYear('encounter_date', $year)->groupBy('m')->pluck('c', 'm')->toArray();
        } catch (\Throwable $e) {}
        $trends = [];
        for ($m = 1; $m <= 12; $m++) {
            $trends[] = [
                'month' => date('M', mktime(0, 0, 0, $m, 1)),
                'patients' => $pc[$m] ?? 0,
                'encounters' => $ec[$m] ?? 0,
                'revenue' => 0,
            ];
        }
        return $trends;
    }
}
