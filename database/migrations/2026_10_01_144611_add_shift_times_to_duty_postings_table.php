<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('duty_postings', function (Blueprint $table) {
            $table->time('shift_start_time')->nullable()->after('start_date');
            $table->time('shift_end_time')->nullable()->after('shift_start_time');
            $table->boolean('is_cashier_shift')->default(false)->after('shift_end_time');
            $table->boolean('is_active_now')->default(false)->after('is_cashier_shift');
            $table->timestamp('last_login_at')->nullable()->after('is_active_now');
            $table->timestamp('force_logout_at')->nullable()->after('last_login_at');
        });
    }

    public function down(): void
    {
        Schema::table('duty_postings', function (Blueprint $table) {
            $table->dropColumn([
                'shift_start_time', 'shift_end_time', 'is_cashier_shift',
                'is_active_now', 'last_login_at', 'force_logout_at',
            ]);
        });
    }
};