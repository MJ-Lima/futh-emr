<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------------
        // Ward Round Notes — daily ward round records by the doctor on
        // call (resident or consultant) for an admitted patient.
        // ---------------------------------------------------------------
        if (!Schema::hasTable('ward_round_notes')) {
            Schema::create('ward_round_notes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
                $table->foreignId('admission_id')->nullable()->constrained('admissions')->cascadeOnDelete();
                $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
                $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
                $table->foreignId('ward_id')->nullable()->constrained('wards')->nullOnDelete();
                $table->foreignId('bed_id')->nullable()->constrained('beds')->nullOnDelete();
                $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
                $table->string('note_number')->unique();
                $table->date('round_date');
                $table->time('round_time');
                $table->enum('round_type', ['Morning', 'Evening', 'Night', 'Consultant Ward Round', 'Emergency Review', 'Handover'])->default('Morning');
                // — Progress / SOAP —
                $table->text('subjective')->nullable()->comment('Patient-reported symptoms, complaints since last review');
                $table->text('objective')->nullable()->comment('Examination findings, vitals, investigations results');
                $table->text('assessment')->nullable()->comment('Working diagnosis, severity, progress');
                $table->text('plan')->nullable()->comment('Investigations, medications, procedures, plan for discharge');
                // — Vitals quick capture (so doctor doesn\'t need separate vitals entry) —
                $table->decimal('temperature', 5, 2)->nullable()->comment('Celsius');
                $table->smallInteger('systolic_bp')->nullable();
                $table->smallInteger('diastolic_bp')->nullable();
                $table->smallInteger('heart_rate')->nullable();
                $table->smallInteger('respiratory_rate')->nullable();
                $table->smallInteger('spo2')->nullable()->comment('Oxygen saturation %');
                $table->decimal('blood_glucose', 5, 2)->nullable()->comment('mmol/L');
                $table->decimal('weight_kg', 6, 2)->nullable();
                $table->text('investigations_ordered')->nullable();
                $table->text('medications_changes')->nullable()->comment('New medications, dose changes, stopped drugs');
                $table->text('plan_for_discharge')->nullable()->comment('Discharge readiness, criteria, expected date');
                $table->text('doctor_notes')->nullable();
                $table->text('nurse_action_required')->nullable()->comment('Specific instructions for nurses on next shift');
                $table->timestamps();
                $table->softDeletes();

                $table->index(['patient_id', 'round_date']);
                $table->index(['ward_id', 'round_date']);
                $table->index(['admission_id', 'round_date']);
                $table->index('doctor_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ward_round_notes');
    }
};
