<?php

namespace App\Filament\Traits;

use App\Models\DutyPosting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

/**
 * Apply hospital / unit scoping to Filament resources.
 *
 * Hierarchy of access:
 *   - super_admin, medical_administrator  → everything (no scope)
 *   - head_of_department                   → all records in their department
 *   - head_of_unit                         → all records in their unit (clinic)
 *   - all other staff                      → only their hospital's records
 */
trait HasUnitScoping
{
    protected static array $hospitalIdTables = [];
    protected static array $departmentIdTables = [];
    protected static array $clinicIdTables = [];

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user  = auth()->user();

        if (! $user) return $query;

        // Level 1: Super admin / medical administrator — see everything
        if ($user->hasRole(['super_admin', 'medical_administrator'])) {
            return $query;
        }

        $model = $query->getModel();
        $tableName = $model->getTable();

        // Cache column existence per table
        if (!isset(self::$hospitalIdTables[$tableName])) {
            try {
                self::$hospitalIdTables[$tableName]  = Schema::hasColumn($tableName, 'hospital_id');
                self::$departmentIdTables[$tableName] = Schema::hasColumn($tableName, 'department_id');
                self::$clinicIdTables[$tableName]     = Schema::hasColumn($tableName, 'clinic_id');
            } catch (\Exception $e) {
                self::$hospitalIdTables[$tableName]  = false;
                self::$departmentIdTables[$tableName] = false;
                self::$clinicIdTables[$tableName]     = false;
            }
        }

        $context = self::currentUserContext();
        if (!$context) {
            return $query;
        }

        // Level 2: Head of Department — see all records in their department
        if ($user->hasRole('head_of_department')) {
            if (!empty($context['department_id']) && self::$departmentIdTables[$tableName]) {
                $query->where('department_id', $context['department_id']);
            } elseif (!empty($context['hospital_id']) && self::$hospitalIdTables[$tableName]) {
                $query->where('hospital_id', $context['hospital_id']);
            }
            return $query;
        }

        // Level 3: Head of Unit — see all records in their unit (clinic)
        if ($user->hasRole('head_of_unit')) {
            if (!empty($context['clinic_id']) && self::$clinicIdTables[$tableName]) {
                $query->where('clinic_id', $context['clinic_id']);
            } elseif (!empty($context['hospital_id']) && self::$hospitalIdTables[$tableName]) {
                $query->where('hospital_id', $context['hospital_id']);
            }
            return $query;
        }

        // Level 4: Regular staff — see only their hospital's records
        if (!empty($context['hospital_id']) && self::$hospitalIdTables[$tableName]) {
            $query->where('hospital_id', $context['hospital_id']);
        }

        return $query;
    }

    /**
     * Get the current user's duty posting context (hospital / department / clinic).
     */
    public static function currentUserContext(): ?array
    {
        $user = auth()->user();
        if (! $user) return null;
        if ($user->hasRole(['super_admin', 'medical_administrator'])) return null;

        $today = now()->toDateString();
        $posting = DutyPosting::where('user_id', $user->id)
            ->where('status', 'Active')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->latest('id')
            ->first();

        if ($posting) {
            return [
                'hospital_id'   => $posting->hospital_id,
                'department_id' => $posting->department_id,
                'clinic_id'     => $posting->clinic_id,
            ];
        }

        return [
            'hospital_id'   => $user->hospital_id,
            'department_id' => $user->department_id,
            'clinic_id'     => $user->default_clinic_id,
        ];
    }
}