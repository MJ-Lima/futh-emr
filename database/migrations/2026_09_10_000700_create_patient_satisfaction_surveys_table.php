<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------------
        // Patient Satisfaction Surveys
        // ---------------------------------------------------------------
        if (!Schema::hasTable('patient_satisfaction_surveys')) {
            Schema::create('patient_satisfaction_surveys', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
                $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
                $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
                $table->string('survey_number')->unique();
                $table->date('survey_date');
                $table->string('department_visited')->nullable();
                $table->string('respondent_name')->nullable();
                $table->string('respondent_phone')->nullable();
                // Rating fields (1-5 scale: 1=Very Poor, 5=Excellent)
                $table->tinyInteger('rating_reception')->nullable();
                $table->tinyInteger('rating_nursing_care')->nullable();
                $table->tinyInteger('rating_doctor_care')->nullable();
                $table->tinyInteger('rating_cleanliness')->nullable();
                $table->tinyInteger('rating_waiting_time')->nullable();
                $table->tinyInteger('rating_pharmacy')->nullable();
                $table->tinyInteger('rating_lab_service')->nullable();
                $table->tinyInteger('rating_overall')->nullable();
                // Free text
                $table->text('best_experience')->nullable();
                $table->text('worst_experience')->nullable();
                $table->text('suggestions')->nullable();
                $table->text('complaints')->nullable();
                $table->boolean('would_recommend')->nullable();
                $table->string('collected_by')->nullable();
                $table->enum('status', ['Pending Review', 'Reviewed', 'Action Taken', 'Archived'])->default('Pending Review');
                $table->text('review_notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->index(['survey_date', 'status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_satisfaction_surveys');
    }
};
