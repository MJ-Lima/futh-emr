<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

/**
 * Registers the two new roles used by the public provider-registration workflow.
 * Safe to re-run (uses firstOrCreate).
 */
class PublicPortalRolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'private_doctor',
                'guard' => 'web',
            ],
            [
                'name' => 'hospital_administrator',
                'guard' => 'web',
            ],
        ];

        foreach ($roles as $r) {
            Role::firstOrCreate(['name' => $r['name'], 'guard_name' => $r['guard']]);
        }

        $this->command->info('Ensured provider roles: ' . implode(', ', array_column($roles, 'name')));
    }
}
