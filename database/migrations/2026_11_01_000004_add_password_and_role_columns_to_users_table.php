<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds `must_change_password` and `default_password` columns to the
 * users table so that newly created users get a system-generated
 * password and are forced to change it on first login.
 *
 * Also adds `active_role` to store the role the user has currently
 * switched to (when they have multiple roles).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'must_change_password')) {
                $table->boolean('must_change_password')->default(false)->after('password');
            }
            if (! Schema::hasColumn('users', 'default_password')) {
                $table->string('default_password')->nullable()->after('must_change_password');
            }
            if (! Schema::hasColumn('users', 'active_role')) {
                $table->string('active_role')->nullable()->after('default_password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['must_change_password', 'default_password', 'active_role'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
