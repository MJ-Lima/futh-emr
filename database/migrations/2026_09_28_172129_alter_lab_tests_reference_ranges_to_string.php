<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lab_tests', function (Blueprint $table) {
            $table->string('reference_range_low', 50)->nullable()->change();
            $table->string('reference_range_high', 50)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('lab_tests', function (Blueprint $table) {
            $table->decimal('reference_range_low', 8, 2)->nullable()->change();
            $table->decimal('reference_range_high', 8, 2)->nullable()->change();
        });
    }
};