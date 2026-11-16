<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

/**
 * Normalize Spatie role names to snake_case.
 *
 * Safe because model_has_roles / role_has_permissions reference roles.id,
 * NOT roles.name — renaming `name` touches no foreign keys.
 *
 * Create it in your project with:
 *   php artisan make:migration normalize_role_names_to_snake_case
 * ...then paste this content into the generated file in database/migrations/.
 */
return new class extends Migration
{
    /**
     * Old name => New name. Renames ALL Title Case roles used in this
     * project to snake_case so hasRole() / assignRole() calls are
     * consistent throughout the codebase.
     */
    private const RENAME_MAP = [
        'Super Admin'              => 'super_admin',
        'Medical Administrator'    => 'medical_administrator',
        'Doctor'                   => 'doctor',
        'Resident Doctor'          => 'resident_doctor',
        'Nurse'                    => 'nurse',
        'Pharmacist'               => 'pharmacist',
        'Laboratory Scientist'     => 'laboratory_scientist',
        'Radiographer'             => 'radiographer',
        'Medical Records Officer'  => 'medical_records_officer',
        'Accountant'               => 'accountant',
        'Insurance Officer'        => 'insurance_officer',
        'Scientific Officer'       => 'scientific_officer',
    ];

    public function up(): void
    {
        foreach (self::RENAME_MAP as $old => $new) {
            // Guard: if a role with the target name already exists (shouldn't,
            // per current data), skip instead of colliding with the unique(name, guard_name) index.
            $targetExists = DB::table('roles')
                ->where('name', $new)
                ->where('guard_name', 'web')
                ->exists();

            if ($targetExists) {
                continue;
            }

            DB::table('roles')
                ->where('name', $old)
                ->where('guard_name', 'web')
                ->update(['name' => $new]);
        }

        $this->flushSpatieCache();
    }

    public function down(): void
    {
        foreach (array_flip(self::RENAME_MAP) as $current => $original) {
            DB::table('roles')
                ->where('name', $current)
                ->where('guard_name', 'web')
                ->update(['name' => $original]);
        }

        $this->flushSpatieCache();
    }

    private function flushSpatieCache(): void
    {
        try {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        } catch (\Throwable $e) {
            // Swallow if Spatie's registrar isn't booted in this context.
        }
    }
};
