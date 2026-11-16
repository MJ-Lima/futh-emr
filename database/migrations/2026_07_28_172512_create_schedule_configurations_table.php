<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedule_configurations', function (Blueprint $table) {
			$table->id();
			$table->foreignId('clinic_id')->constrained('clinics')->cascadeOnDelete();
			$table->unsignedTinyInteger('day_of_week'); // 0 = Sunday ... 6 = Saturday
			$table->time('start_time');
			$table->time('end_time');
			$table->unsignedSmallInteger('slot_duration_minutes')->default(30);
			$table->boolean('is_active')->default(true);
			$table->timestamps();

			$table->unique(['clinic_id', 'day_of_week']);
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_configurations');
    }
};
