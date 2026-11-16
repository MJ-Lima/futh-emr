<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DashboardWidgetSetting extends Model
{
    protected $fillable = ['role_name', 'widget_key', 'is_visible', 'sort_order'];

    protected $casts = [
        'is_visible' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get available widgets with descriptions.
     */
    public const WIDGETS = [
        'kpiCards' => ['label' => 'KPI Stat Cards', 'description' => 'Role-specific key performance indicator cards (patients, encounters, admissions, etc.)'],
        'doctorWorklist' => ['label' => 'Doctor Daily Worklist', 'description' => 'Waiting patients, ward rounds due, pending labs, my inpatients'],
        'charts' => ['label' => 'Charts', 'description' => 'Registration trends, encounters by clinic charts'],
        'quickActions' => ['label' => 'Quick Actions', 'description' => 'Role-specific quick action buttons (register patient, new Rx, etc.)'],
        'bottomTable' => ['label' => 'Clinic Performance Table', 'description' => 'Per-clinic patient totals, completed encounters, wait times'],
        'sideWidgets' => ['label' => 'Side Widgets', 'description' => 'Average wait time, length of stay, pending prescriptions'],
        'recentActivities' => ['label' => 'Recent Activities', 'description' => 'Recent registrations, lab orders, prescriptions, etc.'],
    ];

    public const ROLES = [
        'super_admin' => 'super_admin',
        'medical_administrator' => 'medical_administrator',
        'doctor' => 'doctor',
        'resident_doctor' => 'resident_doctor',
        'nurse' => 'nurse',
        'pharmacist' => 'pharmacist',
        'laboratory_scientist' => 'laboratory_scientist',
        'scientific_officer' => 'scientific_officer',
        'radiographer' => 'radiographer',
        'medical_records_officer' => 'medical_records_officer',
        'accountant' => 'accountant',
        'insurance_officer' => 'insurance_officer',
    ];

    /**
     * Check if a widget should be visible for a given role.
     */
    public static function isWidgetVisible(string $role, string $widgetKey): bool
    {
        $setting = self::where('role_name', $role)->where('widget_key', $widgetKey)->first();
        // Default: visible if no setting exists
        return $setting?->is_visible ?? true;
    }

    /**
     * Get all visible widgets for a role.
     */
    public static function getVisibleWidgets(string $role): array
    {
        $visible = [];
        foreach (array_keys(self::WIDGETS) as $key) {
            if (self::isWidgetVisible($role, $key)) {
                $visible[] = $key;
            }
        }
        return $visible;
    }

    /**
     * Seed default settings for all roles.
     */
    public static function seedDefaults(): void
    {
        foreach (self::ROLES as $role) {
            $order = 0;
            foreach (self::WIDGETS as $key => $widget) {
                // Default visibility: doctorWorklist only for doctors, KPI cards for all
                $visible = true;
                if ($key === 'doctorWorklist') {
                    $visible = in_array($role, ['super_admin', 'doctor', 'resident_doctor', 'medical_administrator']);
                }
                self::firstOrCreate(
                    ['role_name' => $role, 'widget_key' => $key],
                    ['is_visible' => $visible, 'sort_order' => $order++]
                );
            }
        }
    }
}
