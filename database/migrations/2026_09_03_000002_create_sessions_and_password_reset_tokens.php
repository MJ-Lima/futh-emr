<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the default Laravel session infrastructure your hand-built
 * database is missing. Guards with hasTable() so it's safe to run
 * regardless of current state.
 *
 * Create it in the project with:
 *   php artisan make:migration create_sessions_table
 * ...then paste this content into the generated file.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Required by SESSION_DRIVER=database (Laravel default since v11)
        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }

        // Required for "forgot password" flows — usually missing from
        // hand-built databases too, so create it while we're here.
        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
